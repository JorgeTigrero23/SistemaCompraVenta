<?php

use App\User;
use App\Models\People;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $person = People::create([
            'name' => 'Jorge Tigrero',
            'document_type' => 'DNI',
            'document_number' => '0987654321',
            'address' => 'Calle 2 Avenida 23 Enriquez Gallo, La libertad',
            'phone' => '593000000000',
            'email' => 'admin@mail.com'
        ]);
        
        $user = new User();
        $user->username = 'admin';
        $user->password = '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'; // secret
        $user->rol_id = 1;
        $user->id = $person->id;
        $user->save();

        $person1 = People::create([
            'name' => 'Viviana Sanchez',
            'document_type' => 'DNI',
            'document_number' => '0988776655',
            'address' => 'Calle 2 Avenida 23 Enriquez Gallo, La libertad',
            'phone' => '5931111111111',
            'email' => 'vendedor01@mail.com'
        ]);
        
        $user1 = new User();
        $user1->username = 'vendedor01';
        $user1->password = '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'; // secret
        $user1->rol_id = 2;
        $user1->id = $person1->id;
        $user1->save();

        $person2 = People::create([
            'name' => 'Ronald Valdivieso',
            'document_type' => 'DNI',
            'document_number' => '0911223344',
            'address' => 'Calle 2 Avenida 23 Enriquez Gallo, La libertad',
            'phone' => '5932222222222',
            'email' => 'almacenero01@mail.com'
        ]);
        
        $user2 = new User();
        $user2->username = 'almacenero01';
        $user2->password = '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'; // secret
        $user2->rol_id = 3;
        $user2->id = $person2->id;
        $user2->save();
    }
}
