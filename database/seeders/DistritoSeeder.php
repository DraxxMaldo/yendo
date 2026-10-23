<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistritoSeeder extends Seeder
{
    public function run(): void
    {
        $distritos = [
            // ================= DEPARTAMENTO: AHUACHAPÁN =================
            // 1: Ahuachapán Norte
            ['id_municipio' => 1, 'nombre_distrito' => 'Atiquizaya'], ['id_municipio' => 1, 'nombre_distrito' => 'El Refugio'], ['id_municipio' => 1, 'nombre_distrito' => 'San Lorenzo'], ['id_municipio' => 1, 'nombre_distrito' => 'Turín'],
            // 2: Ahuachapán Centro
            ['id_municipio' => 2, 'nombre_distrito' => 'Ahuachapán'], ['id_municipio' => 2, 'nombre_distrito' => 'Apaneca'], ['id_municipio' => 2, 'nombre_distrito' => 'Concepción de Ataco'], ['id_municipio' => 2, 'nombre_distrito' => 'Tacuba'],
            // 3: Ahuachapán Sur
            ['id_municipio' => 3, 'nombre_distrito' => 'Guaymango'], ['id_municipio' => 3, 'nombre_distrito' => 'Jujutla'], ['id_municipio' => 3, 'nombre_distrito' => 'San Francisco Menéndez'], ['id_municipio' => 3, 'nombre_distrito' => 'San Pedro Puxtla'],

            // ================= DEPARTAMENTO: SANTA ANA =================
            // 4: Santa Ana Norte
            ['id_municipio' => 4, 'nombre_distrito' => 'Masahuat'], ['id_municipio' => 4, 'nombre_distrito' => 'Metapán'], ['id_municipio' => 4, 'nombre_distrito' => 'Santa Rosa Guachipilín'], ['id_municipio' => 4, 'nombre_distrito' => 'Texistepeque'],
            // 5: Santa Ana Centro
            ['id_municipio' => 5, 'nombre_distrito' => 'Santa Ana'],
            // 6: Santa Ana Este
            ['id_municipio' => 6, 'nombre_distrito' => 'Coatepeque'], ['id_municipio' => 6, 'nombre_distrito' => 'El Congo'],
            // 7: Santa Ana Oeste
            ['id_municipio' => 7, 'nombre_distrito' => 'Candelaria de la Frontera'], ['id_municipio' => 7, 'nombre_distrito' => 'Chalchuapa'], ['id_municipio' => 7, 'nombre_distrito' => 'El Porvenir'], ['id_municipio' => 7, 'nombre_distrito' => 'San Antonio Pajonal'], ['id_municipio' => 7, 'nombre_distrito' => 'San Sebastián Salitrillo'], ['id_municipio' => 7, 'nombre_distrito' => 'Santiago de la Frontera'],

            // ================= DEPARTAMENTO: SONSONATE =================
            // 8: Sonsonate Norte
            ['id_municipio' => 8, 'nombre_distrito' => 'Juayúa'], ['id_municipio' => 8, 'nombre_distrito' => 'Nahuizalco'], ['id_municipio' => 8, 'nombre_distrito' => 'Salcoatitán'], ['id_municipio' => 8, 'nombre_distrito' => 'Santa Catarina Masahuat'],
            // 9: Sonsonate Centro
            ['id_municipio' => 9, 'nombre_distrito' => 'Sonsonate'], ['id_municipio' => 9, 'nombre_distrito' => 'Sonzacate'], ['id_municipio' => 9, 'nombre_distrito' => 'Nahulingo'], ['id_municipio' => 9, 'nombre_distrito' => 'San Antonio del Monte'], ['id_municipio' => 9, 'nombre_distrito' => 'Santo Domingo de Guzmán'],
            // 10: Sonsonate Este
            ['id_municipio' => 10, 'nombre_distrito' => 'Armenia'], ['id_municipio' => 10, 'nombre_distrito' => 'Caluco'], ['id_municipio' => 10, 'nombre_distrito' => 'Cuisnahuat'], ['id_municipio' => 10, 'nombre_distrito' => 'Izalco'], ['id_municipio' => 10, 'nombre_distrito' => 'San Julián'], ['id_municipio' => 10, 'nombre_distrito' => 'Santa Isabel Ishuatán'],
            // 11: Sonsonate Oeste
            ['id_municipio' => 11, 'nombre_distrito' => 'Acajutla'],

            // ================= DEPARTAMENTO: CHALATENANGO =================
            // 12: Chalatenango Norte
            ['id_municipio' => 12, 'nombre_distrito' => 'La Palma'], ['id_municipio' => 12, 'nombre_distrito' => 'Citalá'], ['id_municipio' => 12, 'nombre_distrito' => 'San Ignacio'],
            // 13: Chalatenango Centro
            ['id_municipio' => 13, 'nombre_distrito' => 'Nueva Concepción'], ['id_municipio' => 13, 'nombre_distrito' => 'Tejutla'], ['id_municipio' => 13, 'nombre_distrito' => 'La Reina'], ['id_municipio' => 13, 'nombre_distrito' => 'Agua Caliente'], ['id_municipio' => 13, 'nombre_distrito' => 'Dulce Nombre de María'], ['id_municipio' => 13, 'nombre_distrito' => 'El Paraíso'], ['id_municipio' => 13, 'nombre_distrito' => 'San Fernando'], ['id_municipio' => 13, 'nombre_distrito' => 'San Francisco Morazán'], ['id_municipio' => 13, 'nombre_distrito' => 'San Rafael'], ['id_municipio' => 13, 'nombre_distrito' => 'Santa Rita'],
            // 14: Chalatenango Sur
            ['id_municipio' => 14, 'nombre_distrito' => 'Chalatenango'], ['id_municipio' => 14, 'nombre_distrito' => 'Arcatao'], ['id_municipio' => 14, 'nombre_distrito' => 'Azacualpa'], ['id_municipio' => 14, 'nombre_distrito' => 'Comalapa'], ['id_municipio' => 14, 'nombre_distrito' => 'Concepción Quezaltepeque'], ['id_municipio' => 14, 'nombre_distrito' => 'El Carrizal'], ['id_municipio' => 14, 'nombre_distrito' => 'La Laguna'], ['id_municipio' => 14, 'nombre_distrito' => 'Las Vueltas'], ['id_municipio' => 14, 'nombre_distrito' => 'Nombre de Jesús'], ['id_municipio' => 14, 'nombre_distrito' => 'Nueva Trinidad'], ['id_municipio' => 14, 'nombre_distrito' => 'Ojos de Agua'], ['id_municipio' => 14, 'nombre_distrito' => 'Potonico'], ['id_municipio' => 14, 'nombre_distrito' => 'San Antonio de la Cruz'], ['id_municipio' => 14, 'nombre_distrito' => 'San Antonio Los Ranchos'], ['id_municipio' => 14, 'nombre_distrito' => 'San Francisco Lempa'], ['id_municipio' => 14, 'nombre_distrito' => 'San Isidro Labrador'], ['id_municipio' => 14, 'nombre_distrito' => 'San José Cancasque'], ['id_municipio' => 14, 'nombre_distrito' => 'San Miguel de Mercedes'], ['id_municipio' => 14, 'nombre_distrito' => 'San José Las Flores'], ['id_municipio' => 14, 'nombre_distrito' => 'San Luis del Carmen'],

            // ================= DEPARTAMENTO: LA LIBERTAD =================
            // 15: La Libertad Norte
            ['id_municipio' => 15, 'nombre_distrito' => 'Quezaltepeque'], ['id_municipio' => 15, 'nombre_distrito' => 'San Matías'], ['id_municipio' => 15, 'nombre_distrito' => 'San Pablo Tacachico'],
            // 16: La Libertad Centro
            ['id_municipio' => 16, 'nombre_distrito' => 'San Juan Opico'], ['id_municipio' => 16, 'nombre_distrito' => 'Ciudad Arce'],
            // 17: La Libertad Oeste
            ['id_municipio' => 17, 'nombre_distrito' => 'Colón'], ['id_municipio' => 17, 'nombre_distrito' => 'Jayaque'], ['id_municipio' => 17, 'nombre_distrito' => 'Sacacoyo'], ['id_municipio' => 17, 'nombre_distrito' => 'Tepecoyo'], ['id_municipio' => 17, 'nombre_distrito' => 'Talnique'],
            // 18: La Libertad Este
            ['id_municipio' => 18, 'nombre_distrito' => 'Antiguo Cuscatlán'], ['id_municipio' => 18, 'nombre_distrito' => 'Huizúcar'], ['id_municipio' => 18, 'nombre_distrito' => 'Nuevo Cuscatlán'], ['id_municipio' => 18, 'nombre_distrito' => 'San José Villanueva'], ['id_municipio' => 18, 'nombre_distrito' => 'Zaragoza'],
            // 19: La Libertad Sur
            ['id_municipio' => 19, 'nombre_distrito' => 'Comasagua'], ['id_municipio' => 19, 'nombre_distrito' => 'Santa Tecla'],
            // 20: La Libertad Costa
            ['id_municipio' => 20, 'nombre_distrito' => 'Chiltiupán'], ['id_municipio' => 20, 'nombre_distrito' => 'Jicalapa'], ['id_municipio' => 20, 'nombre_distrito' => 'La Libertad'], ['id_municipio' => 20, 'nombre_distrito' => 'Tamanique'], ['id_municipio' => 20, 'nombre_distrito' => 'Teotepeque'],

            // ================= DEPARTAMENTO: SAN SALVADOR =================
            // 21: San Salvador Norte
            ['id_municipio' => 21, 'nombre_distrito' => 'Aguilares'], ['id_municipio' => 21, 'nombre_distrito' => 'El Paisnal'], ['id_municipio' => 21, 'nombre_distrito' => 'Guazapa'],
            // 22: San Salvador Oeste
            ['id_municipio' => 22, 'nombre_distrito' => 'Apopa'], ['id_municipio' => 22, 'nombre_distrito' => 'Nejapa'],
            // 23: San Salvador Este
            ['id_municipio' => 23, 'nombre_distrito' => 'Cuscatancingo'], ['id_municipio' => 23, 'nombre_distrito' => 'Ciudad Delgado'], ['id_municipio' => 23, 'nombre_distrito' => 'Ilopango'], ['id_municipio' => 23, 'nombre_distrito' => 'San Martín'], ['id_municipio' => 23, 'nombre_distrito' => 'Soyapango'], ['id_municipio' => 23, 'nombre_distrito' => 'Tonacatepeque'],
            // 24: San Salvador Centro
            ['id_municipio' => 24, 'nombre_distrito' => 'Ayutuxtepeque'], ['id_municipio' => 24, 'nombre_distrito' => 'Mejicanos'], ['id_municipio' => 24, 'nombre_distrito' => 'San Marcos'], ['id_municipio' => 24, 'nombre_distrito' => 'San Salvador'], ['id_municipio' => 24, 'nombre_distrito' => 'Santo Tomás'], ['id_municipio' => 24, 'nombre_distrito' => 'Santiago Texacuangos'],
            // 25: San Salvador Sur
            ['id_municipio' => 25, 'nombre_distrito' => 'Panchimalco'], ['id_municipio' => 25, 'nombre_distrito' => 'Rosario de Mora'],

            // ================= DEPARTAMENTO: CUSCATLÁN =================
            // 26: Cuscatlán Norte
            ['id_municipio' => 26, 'nombre_distrito' => 'Suchitoto'], ['id_municipio' => 26, 'nombre_distrito' => 'San José Guayabal'], ['id_municipio' => 26, 'nombre_distrito' => 'Oratorio de Concepción'], ['id_municipio' => 26, 'nombre_distrito' => 'San Bartolomé Perulapía'], ['id_municipio' => 26, 'nombre_distrito' => 'San Pedro Perulapán'],
            // 27: Cuscatlán Sur
            ['id_municipio' => 27, 'nombre_distrito' => 'Cojutepeque'], ['id_municipio' => 27, 'nombre_distrito' => 'San Rafael Cedros'], ['id_municipio' => 27, 'nombre_distrito' => 'Candelaria'], ['id_municipio' => 27, 'nombre_distrito' => 'Monte San Juan'], ['id_municipio' => 27, 'nombre_distrito' => 'El Carmen'], ['id_municipio' => 27, 'nombre_distrito' => 'San Cristóbal'], ['id_municipio' => 27, 'nombre_distrito' => 'Santa Cruz Michapa'], ['id_municipio' => 27, 'nombre_distrito' => 'San Ramón'], ['id_municipio' => 27, 'nombre_distrito' => 'El Rosario'], ['id_municipio' => 27, 'nombre_distrito' => 'Santa Cruz Analquito'], ['id_municipio' => 27, 'nombre_distrito' => 'Tenancingo'],

            // ================= DEPARTAMENTO: CABAÑAS =================
            // 28: Cabañas Este
            ['id_municipio' => 28, 'nombre_distrito' => 'Sensuntepeque'], ['id_municipio' => 28, 'nombre_distrito' => 'Victoria'], ['id_municipio' => 28, 'nombre_distrito' => 'Villa Dolores'], ['id_municipio' => 28, 'nombre_distrito' => 'Guacotecti'], ['id_municipio' => 28, 'nombre_distrito' => 'San Isidro'],
            // 29: Cabañas Oeste
            ['id_municipio' => 29, 'nombre_distrito' => 'Ilobasco'], ['id_municipio' => 29, 'nombre_distrito' => 'Tejutepeque'], ['id_municipio' => 29, 'nombre_distrito' => 'Jutiapa'], ['id_municipio' => 29, 'nombre_distrito' => 'Cinquera'],

            // ================= DEPARTAMENTO: SAN VICENTE =================
            // 30: San Vicente Norte
            ['id_municipio' => 30, 'nombre_distrito' => 'Apastepeque'], ['id_municipio' => 30, 'nombre_distrito' => 'Santa Clara'], ['id_municipio' => 30, 'nombre_distrito' => 'San Ildefonso'], ['id_municipio' => 30, 'nombre_distrito' => 'San Esteban Catarina'], ['id_municipio' => 30, 'nombre_distrito' => 'San Sebastián'], ['id_municipio' => 30, 'nombre_distrito' => 'San Lorenzo'], ['id_municipio' => 30, 'nombre_distrito' => 'Santo Domingo'],
            // 31: San Vicente Sur
            ['id_municipio' => 31, 'nombre_distrito' => 'San Vicente'], ['id_municipio' => 31, 'nombre_distrito' => 'Guadalupe'], ['id_municipio' => 31, 'nombre_distrito' => 'Verapaz'], ['id_municipio' => 31, 'nombre_distrito' => 'Nuevo Tepetitán'], ['id_municipio' => 31, 'nombre_distrito' => 'Tecoluca'], ['id_municipio' => 31, 'nombre_distrito' => 'San Cayetano Istepeque'],

            // ================= DEPARTAMENTO: LA PAZ =================
            // 32: La Paz Oeste
            ['id_municipio' => 32, 'nombre_distrito' => 'Cuyultitán'], ['id_municipio' => 32, 'nombre_distrito' => 'Olocuilta'], ['id_municipio' => 32, 'nombre_distrito' => 'San Juan Talpa'], ['id_municipio' => 32, 'nombre_distrito' => 'San Luis Talpa'], ['id_municipio' => 32, 'nombre_distrito' => 'San Pedro Masahuat'], ['id_municipio' => 32, 'nombre_distrito' => 'Tapalhuaca'], ['id_municipio' => 32, 'nombre_distrito' => 'San Francisco Chinameca'],
            // 33: La Paz Centro
            ['id_municipio' => 33, 'nombre_distrito' => 'El Rosario'], ['id_municipio' => 33, 'nombre_distrito' => 'Jerusalén'], ['id_municipio' => 33, 'nombre_distrito' => 'Mercedes La Ceiba'], ['id_municipio' => 33, 'nombre_distrito' => 'Paraíso de Osorio'], ['id_municipio' => 33, 'nombre_distrito' => 'San Antonio Masahuat'], ['id_municipio' => 33, 'nombre_distrito' => 'San Emigdio'], ['id_municipio' => 33, 'nombre_distrito' => 'San Juan Tepezontes'], ['id_municipio' => 33, 'nombre_distrito' => 'San Luis La Herradura'], ['id_municipio' => 33, 'nombre_distrito' => 'San Miguel Tepezontes'], ['id_municipio' => 33, 'nombre_distrito' => 'San Pedro Nonualco'], ['id_municipio' => 33, 'nombre_distrito' => 'Santa María Ostuma'], ['id_municipio' => 33, 'nombre_distrito' => 'Santiago Nonualco'],
            // 34: La Paz Este
            ['id_municipio' => 34, 'nombre_distrito' => 'San Juan Nonualco'], ['id_municipio' => 34, 'nombre_distrito' => 'San Rafael Obrajuelo'], ['id_municipio' => 34, 'nombre_distrito' => 'Zacatecoluca'],

            // ================= DEPARTAMENTO: USULUTÁN =================
            // 35: Usulután Norte
            ['id_municipio' => 35, 'nombre_distrito' => 'Santiago de María'], ['id_municipio' => 35, 'nombre_distrito' => 'Alegría'], ['id_municipio' => 35, 'nombre_distrito' => 'Berlín'], ['id_municipio' => 35, 'nombre_distrito' => 'Mercedes Umaña'], ['id_municipio' => 35, 'nombre_distrito' => 'Jucuapa'], ['id_municipio' => 35, 'nombre_distrito' => 'El Triunfo'], ['id_municipio' => 35, 'nombre_distrito' => 'Estanzuelas'], ['id_municipio' => 35, 'nombre_distrito' => 'San Buenaventura'], ['id_municipio' => 35, 'nombre_distrito' => 'Nueva Granada'],
            // 36: Usulután Este
            ['id_municipio' => 36, 'nombre_distrito' => 'Usulután'], ['id_municipio' => 36, 'nombre_distrito' => 'Jucuarán'], ['id_municipio' => 36, 'nombre_distrito' => 'San Dionisio'], ['id_municipio' => 36, 'nombre_distrito' => 'Concepción Batres'], ['id_municipio' => 36, 'nombre_distrito' => 'Santa María'], ['id_municipio' => 36, 'nombre_distrito' => 'Ozatlán'], ['id_municipio' => 36, 'nombre_distrito' => 'Tecapán'], ['id_municipio' => 36, 'nombre_distrito' => 'Santa Elena'], ['id_municipio' => 36, 'nombre_distrito' => 'California'], ['id_municipio' => 36, 'nombre_distrito' => 'Ereguayquín'],
            // 37: Usulután Oeste
            ['id_municipio' => 37, 'nombre_distrito' => 'Jiquilisco'], ['id_municipio' => 37, 'nombre_distrito' => 'Puerto El Triunfo'], ['id_municipio' => 37, 'nombre_distrito' => 'San Agustín'], ['id_municipio' => 37, 'nombre_distrito' => 'San Francisco Javier'],

            // ================= DEPARTAMENTO: SAN MIGUEL =================
            // 38: San Miguel Norte
            ['id_municipio' => 38, 'nombre_distrito' => 'Ciudad Barrios'], ['id_municipio' => 38, 'nombre_distrito' => 'Sesori'], ['id_municipio' => 38, 'nombre_distrito' => 'Nuevo Edén de San Juan'], ['id_municipio' => 38, 'nombre_distrito' => 'San Gerardo'], ['id_municipio' => 38, 'nombre_distrito' => 'San Luis La Reina'], ['id_municipio' => 38, 'nombre_distrito' => 'Carolina'], ['id_municipio' => 38, 'nombre_distrito' => 'San Antonio del Mosco'], ['id_municipio' => 38, 'nombre_distrito' => 'Chapeltique'],
            // 39: San Miguel Centro
            ['id_municipio' => 39, 'nombre_distrito' => 'San Miguel'], ['id_municipio' => 39, 'nombre_distrito' => 'Comacarán'], ['id_municipio' => 39, 'nombre_distrito' => 'Uluazapa'], ['id_municipio' => 39, 'nombre_distrito' => 'Moncagua'], ['id_municipio' => 39, 'nombre_distrito' => 'Quelepa'], ['id_municipio' => 39, 'nombre_distrito' => 'Chirilagua'],
            // 40: San Miguel Oeste
            ['id_municipio' => 40, 'nombre_distrito' => 'Chinameca'], ['id_municipio' => 40, 'nombre_distrito' => 'Nueva Guadalupe'], ['id_municipio' => 40, 'nombre_distrito' => 'Lolotique'], ['id_municipio' => 40, 'nombre_distrito' => 'San Jorge'], ['id_municipio' => 40, 'nombre_distrito' => 'San Rafael Oriente'], ['id_municipio' => 40, 'nombre_distrito' => 'El Tránsito'],

            // ================= DEPARTAMENTO: MORAZÁN =================
            // 41: Morazán Norte
            ['id_municipio' => 41, 'nombre_distrito' => 'Arambala'], ['id_municipio' => 41, 'nombre_distrito' => 'Cacaopera'], ['id_municipio' => 41, 'nombre_distrito' => 'Corinto'], ['id_municipio' => 41, 'nombre_distrito' => 'El Rosario'], ['id_municipio' => 41, 'nombre_distrito' => 'Joateca'], ['id_municipio' => 41, 'nombre_distrito' => 'Jocoaitique'], ['id_municipio' => 41, 'nombre_distrito' => 'Meanguera'], ['id_municipio' => 41, 'nombre_distrito' => 'Perquín'], ['id_municipio' => 41, 'nombre_distrito' => 'San Fernando'], ['id_municipio' => 41, 'nombre_distrito' => 'San Isidro'], ['id_municipio' => 41, 'nombre_distrito' => 'Torola'],
            // 42: Morazán Sur
            ['id_municipio' => 42, 'nombre_distrito' => 'Chilanga'], ['id_municipio' => 42, 'nombre_distrito' => 'Delicias de Concepción'], ['id_municipio' => 42, 'nombre_distrito' => 'El Divisadero'], ['id_municipio' => 42, 'nombre_distrito' => 'Gualococti'], ['id_municipio' => 42, 'nombre_distrito' => 'Guatajiagua'], ['id_municipio' => 42, 'nombre_distrito' => 'Jocoro'], ['id_municipio' => 42, 'nombre_distrito' => 'Lolotiquillo'], ['id_municipio' => 42, 'nombre_distrito' => 'Osicala'], ['id_municipio' => 42, 'nombre_distrito' => 'San Carlos'], ['id_municipio' => 42, 'nombre_distrito' => 'San Francisco Gotera'], ['id_municipio' => 42, 'nombre_distrito' => 'San Simón'], ['id_municipio' => 42, 'nombre_distrito' => 'Sensembra'], ['id_municipio' => 42, 'nombre_distrito' => 'Sociedad'], ['id_municipio' => 42, 'nombre_distrito' => 'Yamabal'], ['id_municipio' => 42, 'nombre_distrito' => 'Yoloaiquín'],

            // ================= DEPARTAMENTO: LA UNIÓN =================
            // 43: La Unión Norte
            ['id_municipio' => 43, 'nombre_distrito' => 'Anamorós'], ['id_municipio' => 43, 'nombre_distrito' => 'Bolívar'], ['id_municipio' => 43, 'nombre_distrito' => 'Concepción de Oriente'], ['id_municipio' => 43, 'nombre_distrito' => 'El Sauce'], ['id_municipio' => 43, 'nombre_distrito' => 'Lislique'], ['id_municipio' => 43, 'nombre_distrito' => 'Nueva Esparta'], ['id_municipio' => 43, 'nombre_distrito' => 'Pasaquina'], ['id_municipio' => 43, 'nombre_distrito' => 'Polorós'], ['id_municipio' => 43, 'nombre_distrito' => 'San José La Fuente'], ['id_municipio' => 43, 'nombre_distrito' => 'Santa Rosa de Lima'],
            // 44: La Unión Sur
            ['id_municipio' => 44, 'nombre_distrito' => 'Conchagua'], ['id_municipio' => 44, 'nombre_distrito' => 'El Carmen'], ['id_municipio' => 44, 'nombre_distrito' => 'Intipucá'], ['id_municipio' => 44, 'nombre_distrito' => 'La Unión'], ['id_municipio' => 44, 'nombre_distrito' => 'Meanguera del Golfo'], ['id_municipio' => 44, 'nombre_distrito' => 'San Alejo'], ['id_municipio' => 44, 'nombre_distrito' => 'Yayantique'], ['id_municipio' => 44, 'nombre_distrito' => 'Yucuaiquín'],
        ];

        // Insertamos los datos mediante inserción masiva para máxima eficiencia[cite: 9]
        DB::table('distritos')->insert($distritos);
    }
}
