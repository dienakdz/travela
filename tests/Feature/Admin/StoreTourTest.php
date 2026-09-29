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
