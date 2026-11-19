<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DatabaseIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('password');
            $table->string('role');
            $table->string('nis')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('kantin', function (Blueprint $table) {
            $table->increments('id_kantin');
            $table->string('nama_kantin');
        });

        Schema::create('menu', function (Blueprint $table) {
            $table->increments('id_menu');
            $table->unsignedInteger('id_kantin');
            $table->string('nama_menu');
            $table->string('gambar')->nullable();
            $table->string('kategori');
            $table->decimal('harga', 12, 2);
            $table->unsignedInteger('stok')->default(0);
        });

        Schema::create('transaksi', function (Blueprint $table) {
            $table->increments('id_transaksi');
            $table->string('nis');
            $table->unsignedInteger('id_kantin');
            $table->timestamp('tanggal')->useCurrent();
            $table->string('metode_bayar');
            $table->string('status_bayar')->default('Belum Lunas');
            $table->string('status')->default('Menunggu Konfirmasi');
        });

        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->increments('id_detail');
            $table->unsignedInteger('id_transaksi');
            $table->unsignedInteger('id_menu');
            $table->unsignedInteger('jumlah');
            $table->decimal('harga_saat_transaksi', 12, 2);
            $table->decimal('subtotal', 12, 2);
        });
    }

    public function test_login_accepts_passwords_hashed_in_the_database(): void
    {
        DB::table('users')->insert([
            'username' => 'admin_kantin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'username' => 'admin_kantin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertSame('admin', session('role'));
    }

    public function test_student_order_creates_a_transaction_and_detail_row(): void
    {
        DB::table('users')->insert([
            'id' => 1,
            'username' => 'budi_siswa',
            'password' => Hash::make('123'),
            'role' => 'siswa',
            'nis' => '2024101',
            'aktif' => true,
        ]);

        $menuId = DB::table('menu')->insertGetId([
            'id_kantin' => 3,
            'nama_menu' => 'Es Teh',
            'kategori' => 'Minuman',
            'harga' => 5000,
            'stok' => 10,
        ], 'id_menu');

        $response = $this->withSession([
            'user_id' => 1,
            'role' => 'siswa',
        ])->post('/siswa/pesan', [
            'menu_id' => $menuId,
            'jumlah' => 2,
            'metode_bayar' => 'QRIS',
        ]);

        $response->assertRedirect(route('siswa.dashboard'));
        $this->assertDatabaseHas('transaksi', [
            'nis' => '2024101',
            'id_kantin' => 3,
            'metode_bayar' => 'QRIS',
        ]);
        $this->assertDatabaseHas('detail_transaksi', [
            'id_menu' => $menuId,
            'jumlah' => 2,
            'harga_saat_transaksi' => 5000,
            'subtotal' => 10000,
        ]);
    }

    public function test_role_dashboards_render_with_the_bi_kantin_schema(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('menu/es-teh.png', 'image content');

        DB::table('kantin')->insert([
            'id_kantin' => 1,
            'nama_kantin' => 'Kantin Utama',
        ]);
        DB::table('users')->insert([
            'id' => 1,
            'username' => 'admin_kantin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'username' => 'budi_siswa',
            'password' => Hash::make('123'),
            'role' => 'siswa',
            'nis' => '2024101',
            'aktif' => true,
        ]);
        DB::table('menu')->insert([
            'id_kantin' => 1,
            'nama_menu' => 'Es Teh',
            'gambar' => 'menu/es-teh.png',
            'kategori' => 'Minuman',
            'harga' => 5000,
            'stok' => 10,
        ]);

        $adminResponse = $this->withSession([
            'user_id' => 1,
            'username' => 'admin_kantin',
            'role' => 'admin',
        ])->get('/admin');
        $adminResponse->assertOk()->assertSee('storage/menu/es-teh.png');

        $studentResponse = $this->withSession([
            'user_id' => 2,
            'username' => 'budi_siswa',
            'role' => 'siswa',
        ])->get('/siswa');
        $studentResponse->assertOk()->assertSee('storage/menu/es-teh.png');
    }

    public function test_admin_can_create_a_menu_with_an_image(): void
    {
        Storage::fake('public');

        DB::table('kantin')->insert([
            'id_kantin' => 1,
            'nama_kantin' => 'Kantin Utama',
        ]);

        $response = $this->withSession([
            'user_id' => 1,
            'role' => 'admin',
        ])->post('/admin/menu', [
            'id_kantin' => 1,
            'nama_menu' => 'Es Teh',
            'harga' => 5000,
            'kategori' => 'Minuman',
            'gambar' => UploadedFile::fake()->createWithContent(
                'es-teh.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jp1sAAAAASUVORK5CYII=')
            ),
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $menu = DB::table('menu')->where('nama_menu', 'Es Teh')->first();

        $this->assertNotNull($menu->gambar);
        $this->assertStringStartsWith('menu/', $menu->gambar);
        Storage::disk('public')->assertExists($menu->gambar);
    }

    public function test_admin_can_replace_a_menu_image(): void
    {
        Storage::fake('public');

        DB::table('kantin')->insert([
            'id_kantin' => 1,
            'nama_kantin' => 'Kantin Utama',
        ]);
        $oldImage = UploadedFile::fake()->createWithContent(
            'lama.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jp1sAAAAASUVORK5CYII=')
        );
        $oldPath = $oldImage->store('menu', 'public');
        $menuId = DB::table('menu')->insertGetId([
            'id_kantin' => 1,
            'nama_menu' => 'Es Teh',
            'gambar' => $oldPath,
            'kategori' => 'Minuman',
            'harga' => 5000,
            'stok' => 10,
        ], 'id_menu');

        $response = $this->withSession([
            'user_id' => 1,
            'role' => 'admin',
        ])->put('/admin/menu/'.$menuId, [
            'id_kantin' => 1,
            'nama_menu' => 'Es Teh',
            'harga' => 5000,
            'kategori' => 'Minuman',
            'gambar' => UploadedFile::fake()->createWithContent(
                'baru.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jp1sAAAAASUVORK5CYII=')
            ),
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $updatedMenu = DB::table('menu')->where('id_menu', $menuId)->first();

        $this->assertNotSame($oldPath, $updatedMenu->gambar);
        Storage::disk('public')->assertExists($updatedMenu->gambar);
        Storage::disk('public')->assertMissing($oldPath);
    }

    public function test_admin_cannot_upload_a_non_image_as_a_menu_image(): void
    {
        Storage::fake('public');

        DB::table('kantin')->insert([
            'id_kantin' => 1,
            'nama_kantin' => 'Kantin Utama',
        ]);

        $response = $this->withSession([
            'user_id' => 1,
            'role' => 'admin',
        ])->post('/admin/menu', [
            'id_kantin' => 1,
            'nama_menu' => 'Es Teh',
            'harga' => 5000,
            'kategori' => 'Minuman',
            'gambar' => UploadedFile::fake()->create('gambar.txt', 10, 'text/plain'),
        ]);

        $response->assertSessionHasErrors('gambar');
        $this->assertDatabaseMissing('menu', ['nama_menu' => 'Es Teh']);
    }
}
