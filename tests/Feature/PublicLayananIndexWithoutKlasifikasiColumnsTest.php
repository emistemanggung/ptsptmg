<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PublicLayananIndexWithoutKlasifikasiColumnsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('layanan', function ($table) {
            $table->id();
            $table->string('nama_layanan');
            $table->text('deskripsi')->nullable();
            $table->string('kode_layanan')->unique();
            $table->timestamps();
        });
    }

    public function test_home_page_loads_when_klasifikasi_columns_are_missing(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
