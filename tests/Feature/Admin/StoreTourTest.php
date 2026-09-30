<?php

namespace Tests\Feature\Admin;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StoreTourTest extends TestCase
{
    use WithFaker;

    private string $imageDirectory;

    private string $temporaryDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'tour_testing',
            'database.connections.tour_testing' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        ]);

        DB::purge('tour_testing');
        DB::setDefaultConnection('tour_testing');

        $this->createSchema();

        $suffix = uniqid('store-tour-', true);
        $this->imageDirectory = storage_path('framework/testing/'.$suffix.'/images');
        $this->temporaryDirectory = storage_path('framework/testing/'.$suffix.'/tmp');

        config([
            'tours.images.path' => $this->imageDirectory,
            'tours.images.temporary_path' => $this->temporaryDirectory,
        ]);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(dirname($this->imageDirectory));
        parent::tearDown();
    }

    public function test_it_creates_a_complete_tour_atomically(): void
    {
        $response = $this
            ->withSession(['admin' => 'admin'])
            ->postJson(route('admin.tours.store'), $this->validPayload());

        $response
            ->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertSame(1, DB::table('tbl_tours')->count());
        $this->assertSame(5, DB::table('tbl_images')->count());
        $this->assertSame(2, DB::table('tbl_timeline')->count());
        $this->assertSame(1, (int) DB::table('tbl_tours')->value('availability'));
        $this->assertCount(5, File::files($this->imageDirectory));
    }

    public function test_it_rejects_an_incomplete_image_set_without_writing_data(): void
    {
        $payload = $this->validPayload();
        array_pop($payload['images']);

        $response = $this
            ->withSession(['admin' => 'admin'])
            ->postJson(route('admin.tours.store'), $payload);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('images');

        $this->assertSame(0, DB::table('tbl_tours')->count());
        $this->assertFalse(File::exists($this->imageDirectory));
    }

    public function test_it_rolls_back_database_and_files_when_a_related_insert_fails(): void
    {
        Schema::drop('tbl_timeline');

        $response = $this
            ->withSession(['admin' => 'admin'])
            ->postJson(route('admin.tours.store'), $this->validPayload());

        $response
            ->assertServerError()
            ->assertJsonPath('success', false);

        $this->assertSame(0, DB::table('tbl_tours')->count());
        $this->assertSame(0, DB::table('tbl_images')->count());
        $this->assertTrue(
            ! File::exists($this->imageDirectory)
            || count(File::files($this->imageDirectory)) === 0
        );
    }

    public function test_legacy_step_routes_are_removed(): void
    {
        $this->assertTrue(Route::has('admin.tours.store'));
        $this->assertFalse(Route::has('admin.add-tours'));
        $this->assertFalse(Route::has('admin.add-images-tours'));
        $this->assertFalse(Route::has('admin.add-timeline'));
        $this->assertFalse(Route::has('admin.add-temp-images'));
    }

    public function test_it_updates_a_tour_and_its_relations_atomically(): void
    {
        $tourId = $this->seedEditableTour();
        $payload = $this->validUpdatePayload($tourId);

        $response = $this
            ->withSession(['admin' => 'admin'])
            ->postJson(route('admin.edit-tour'), $payload);

        $response
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSame(
            'Tour transaction updated',
            DB::table('tbl_tours')->where('tourId', $tourId)->value('title')
        );
        $this->assertSame(5, DB::table('tbl_images')->where('tourId', $tourId)->count());
        $this->assertSame(2, DB::table('tbl_timeline')->where('tourId', $tourId)->count());
        $this->assertFalse(
            DB::table('tbl_images')
                ->where('tourId', $tourId)
                ->where('imageURL', 'old-five.jpg')
                ->exists()
        );
        $this->assertFalse(File::exists($this->imageDirectory.'/old-five.jpg'));
        $this->assertTrue(File::exists($this->imageDirectory.'/old-one.jpg'));
        $this->assertCount(5, File::files($this->imageDirectory));
    }

    public function test_update_rolls_back_database_and_new_files_when_timeline_insert_fails(): void
    {
        $tourId = $this->seedEditableTour();

        DB::unprepared(
            "CREATE TRIGGER fail_timeline_insert
            BEFORE INSERT ON tbl_timeline
            BEGIN
                SELECT RAISE(FAIL, 'timeline insert failed');
            END;"
        );

        $response = $this
            ->withSession(['admin' => 'admin'])
            ->postJson(route('admin.edit-tour'), $this->validUpdatePayload($tourId));

        $response
            ->assertServerError()
            ->assertJsonPath('success', false);

        $this->assertSame(
            'Original tour',
            DB::table('tbl_tours')->where('tourId', $tourId)->value('title')
        );
        $this->assertSame(5, DB::table('tbl_images')->where('tourId', $tourId)->count());
        $this->assertSame(1, DB::table('tbl_timeline')->where('tourId', $tourId)->count());
        $this->assertTrue(
            DB::table('tbl_images')
                ->where('tourId', $tourId)
                ->where('imageURL', 'old-five.jpg')
                ->exists()
        );
        $this->assertTrue(File::exists($this->imageDirectory.'/old-five.jpg'));
        $this->assertCount(5, File::files($this->imageDirectory));
    }

    public function test_it_deletes_a_tour_and_its_files_atomically(): void
    {
        $tourId = $this->seedEditableTour();

        $response = $this
            ->withSession(['admin' => 'admin'])
            ->postJson(route('admin.delete-tour'), ['tourId' => $tourId]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertFalse(DB::table('tbl_tours')->where('tourId', $tourId)->exists());
        $this->assertSame(0, DB::table('tbl_images')->where('tourId', $tourId)->count());
        $this->assertSame(0, DB::table('tbl_timeline')->where('tourId', $tourId)->count());
        $this->assertCount(0, File::files($this->imageDirectory));
    }

    public function test_delete_rolls_back_relations_when_deleting_the_tour_fails(): void
    {
        $tourId = $this->seedEditableTour();

        DB::unprepared(
            "CREATE TRIGGER fail_tour_delete
            BEFORE DELETE ON tbl_tours
            BEGIN
                SELECT RAISE(FAIL, 'tour delete failed');
            END;"
        );

        $response = $this
            ->withSession(['admin' => 'admin'])
            ->postJson(route('admin.delete-tour'), ['tourId' => $tourId]);

        $response
            ->assertServerError()
            ->assertJsonPath('success', false);

        $this->assertTrue(DB::table('tbl_tours')->where('tourId', $tourId)->exists());
        $this->assertSame(5, DB::table('tbl_images')->where('tourId', $tourId)->count());
        $this->assertSame(1, DB::table('tbl_timeline')->where('tourId', $tourId)->count());
        $this->assertCount(5, File::files($this->imageDirectory));
    }

    public function test_it_rejects_deleting_a_tour_with_dependent_business_data(): void
    {
        $tourId = $this->seedEditableTour();

        Schema::create('tbl_booking', function (Blueprint $table) {
            $table->increments('bookingId');
            $table->unsignedInteger('tourId');
        });
        DB::table('tbl_booking')->insert(['tourId' => $tourId]);

        $response = $this
            ->withSession(['admin' => 'admin'])
            ->postJson(route('admin.delete-tour'), ['tourId' => $tourId]);

        $response
            ->assertStatus(409)
            ->assertJsonPath('success', false)
            ->assertJsonPath(
                'message',
                'Không thể xóa tour đã có booking, lịch sử hoặc đánh giá.'
            );

        $this->assertTrue(DB::table('tbl_tours')->where('tourId', $tourId)->exists());
        $this->assertSame(5, DB::table('tbl_images')->where('tourId', $tourId)->count());
        $this->assertSame(1, DB::table('tbl_timeline')->where('tourId', $tourId)->count());
        $this->assertCount(5, File::files($this->imageDirectory));
    }

    private function validPayload(): array
    {
        return [
            'name' => 'Tour transaction test',
            'destination' => 'Đà Nẵng',
            'domain' => 't',
            'number' => 20,
            'price_adult' => 1500000,
            'price_child' => 750000,
            'start_date' => now()->addMonth()->format('Y-m-d'),
            'end_date' => now()->addMonth()->addDays(2)->format('Y-m-d'),
            'description' => 'Mô tả tour kiểm thử transaction.',
            'images' => [
                UploadedFile::fake()->image('one.jpg', 800, 600),
                UploadedFile::fake()->image('two.jpg', 800, 600),
                UploadedFile::fake()->image('three.jpg', 800, 600),
                UploadedFile::fake()->image('four.jpg', 800, 600),
                UploadedFile::fake()->image('five.jpg', 800, 600),
            ],
            'timelines' => [
                [
                    'title' => 'Ngày 1',
                    'description' => 'Lịch trình ngày đầu tiên.',
                ],
                [
                    'title' => 'Ngày 2',
                    'description' => 'Lịch trình ngày thứ hai.',
                ],
            ],
        ];
    }

    private function seedEditableTour(): int
    {
        $tourId = DB::table('tbl_tours')->insertGetId([
            'title' => 'Original tour',
            'time' => '2 ngày 1 đêm',
            'description' => 'Original description',
            'quantity' => 10,
            'priceAdult' => 1000000,
            'priceChild' => 500000,
            'destination' => 'Huế',
            'domain' => 't',
            'availability' => 1,
            'startDate' => now()->addMonth()->format('Y-m-d'),
            'endDate' => now()->addMonth()->addDays(2)->format('Y-m-d'),
        ]);

        File::ensureDirectoryExists($this->imageDirectory);

        foreach (['one', 'two', 'three', 'four', 'five'] as $name) {
            $filename = "old-{$name}.jpg";
            File::put($this->imageDirectory.'/'.$filename, 'old image');
            DB::table('tbl_images')->insert([
                'tourId' => $tourId,
                'imageURL' => $filename,
                'description' => $name,
            ]);
        }

        DB::table('tbl_timeline')->insert([
            'tourId' => $tourId,
            'title' => 'Ngày cũ',
            'description' => 'Lộ trình cũ',
        ]);

        return $tourId;
    }

    private function validUpdatePayload(int $tourId): array
    {
        return [
            'tourId' => $tourId,
            'name' => 'Tour transaction updated',
            'destination' => 'Đà Nẵng',
            'domain' => 't',
            'number' => 15,
            'price_adult' => 1800000,
            'price_child' => 900000,
            'description' => 'Updated description',
            'existing_images' => [
                'old-one.jpg',
                'old-two.jpg',
                'old-three.jpg',
                'old-four.jpg',
            ],
            'new_images' => [
                UploadedFile::fake()->image('new-five.jpg', 800, 600),
            ],
            'timelines' => [
                [
                    'title' => 'Ngày 1',
                    'description' => 'Lộ trình mới ngày 1',
                ],
                [
                    'title' => 'Ngày 2',
                    'description' => 'Lộ trình mới ngày 2',
                ],
            ],
        ];
    }

    private function createSchema(): void
    {
        Schema::create('tbl_tours', function (Blueprint $table) {
            $table->increments('tourId');
            $table->string('title');
            $table->string('time');
            $table->text('description');
            $table->integer('quantity');
            $table->double('priceAdult');
            $table->double('priceChild');
            $table->string('destination');
            $table->string('domain');
            $table->boolean('availability');
            $table->date('startDate');
            $table->date('endDate');
        });

        Schema::create('tbl_images', function (Blueprint $table) {
            $table->increments('imageId');
            $table->unsignedInteger('tourId');
            $table->string('imageURL');
            $table->string('description')->nullable();
        });

        Schema::create('tbl_timeline', function (Blueprint $table) {
            $table->increments('timeLineId');
            $table->unsignedInteger('tourId');
            $table->string('title');
            $table->text('description');
        });
    }
}
