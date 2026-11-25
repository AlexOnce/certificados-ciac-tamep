<?php

namespace Database\Seeders;

use App\Models\Certificado;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class CiacEstudiantesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Usuario::where('correo_usu', 'admin@tamep.bo')->first() ?? Usuario::first();
        $adminId = $admin ? $admin->id_usu : 1;

        $docente = 'Lic. MSc. Elsa Quenta Mamani';
        $fechaEmision = '2026-12-18';

        $datos = [
            // ==============================================================
            // 1. CURSO: DESPACHADOR DE VUELO (DV-2026)
            // ==============================================================
            [
                'paterno' => 'CESPEDES', 'materno' => 'LEDEZMA', 'nombres' => 'ARLETH',
                'ci' => '9895606', 'cod_curso' => 'DV/2026', 'curso' => 'DESPACHADOR DE VUELO',
                'periodo' => '02/mar al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP DV 2026 CI 9895606 N° 001/26',
                'reg_estudios' => 'N° 001-DV 2026',
                'codigo_cer' => 'CERT-2026-DV-001',
            ],
            [
                'paterno' => 'CONDE', 'materno' => 'VELASCO', 'nombres' => 'PAOLA',
                'ci' => '6906775', 'cod_curso' => 'DV/2026', 'curso' => 'DESPACHADOR DE VUELO',
                'periodo' => '02/mar al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP DV 2026 CI 6906775 N° 002/26',
                'reg_estudios' => 'N° 002-DV 2026',
                'codigo_cer' => 'CERT-2026-DV-002',
            ],
            [
                'paterno' => 'CORDERO', 'materno' => 'ARUQUIPA', 'nombres' => 'GADIEL FREDDY',
                'ci' => '12831153', 'cod_curso' => 'DV/2026', 'curso' => 'DESPACHADOR DE VUELO',
                'periodo' => '02/mar al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP DV 2026 CI 12831153 N° 003/26',
                'reg_estudios' => 'N° 003-DV 2026',
                'codigo_cer' => 'CERT-2026-DV-003',
            ],
            [
                'paterno' => 'CRUZ', 'materno' => 'VILLCA', 'nombres' => 'JOEL',
                'ci' => '13736718', 'cod_curso' => 'DV/2026', 'curso' => 'DESPACHADOR DE VUELO',
                'periodo' => '02/mar al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP DV 2026 CI 13736718 N° 004/26',
                'reg_estudios' => 'N° 004-DV 2026',
                'codigo_cer' => 'CERT-2026-DV-004',
            ],
            [
                'paterno' => 'CUSICANQUI', 'materno' => 'QUISPE', 'nombres' => 'ANGEL GABRIEL',
                'ci' => '12421197', 'cod_curso' => 'DV/2026', 'curso' => 'DESPACHADOR DE VUELO',
                'periodo' => '02/mar al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP DV 2026 CI 12421197 N° 005/26',
                'reg_estudios' => 'N° 005-DV 2026',
                'codigo_cer' => 'CERT-2026-DV-005',
            ],
            [
                'paterno' => 'FLORES', 'materno' => 'ESPINOZA', 'nombres' => 'BISMAR RODRIGO',
                'ci' => '8436336', 'cod_curso' => 'DV/2026', 'curso' => 'DESPACHADOR DE VUELO',
                'periodo' => '02/mar al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP DV 2026 CI 8436336 N° 006/26',
                'reg_estudios' => 'N° 006-DV 2026',
                'codigo_cer' => 'CERT-2026-DV-006',
            ],
            [
                'paterno' => 'GUTIERREZ', 'materno' => 'CONDORI', 'nombres' => 'ELIAS JOSE',
                'ci' => '6005860', 'cod_curso' => 'DV/2026', 'curso' => 'DESPACHADOR DE VUELO',
                'periodo' => '02/mar al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP DV 2026 CI 6005860 N° 007/26',
                'reg_estudios' => 'N° 007-DV 2026',
                'codigo_cer' => 'CERT-2026-DV-007',
            ],
            [
                'paterno' => 'HUAYGUA', 'materno' => 'LIMACHI', 'nombres' => 'JOSETH NATALY',
                'ci' => '8305201', 'cod_curso' => 'DV/2026', 'curso' => 'DESPACHADOR DE VUELO',
                'periodo' => '02/mar al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP DV 2026 CI 8305201 N° 008/26',
                'reg_estudios' => 'N° 008-DV 2026',
                'codigo_cer' => 'CERT-2026-DV-008',
            ],
            [
                'paterno' => 'LLUSCO', 'materno' => 'TORREZ', 'nombres' => 'OSKAR ANDERSON',
                'ci' => '13020002', 'cod_curso' => 'DV/2026', 'curso' => 'DESPACHADOR DE VUELO',
                'periodo' => '02/mar al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP DV 2026 CI 13020002 N° 009/26',
                'reg_estudios' => 'N° 009-DV 2026',
                'codigo_cer' => 'CERT-2026-DV-009',
            ],
            [
                'paterno' => 'MAMANI', 'materno' => 'LOPEZ', 'nombres' => 'GROVER RODRIGO',
                'ci' => '11060664', 'cod_curso' => 'DV/2026', 'curso' => 'DESPACHADOR DE VUELO',
                'periodo' => '02/mar al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP DV 2026 CI 11060664 N° 010/26',
                'reg_estudios' => 'N° 010-DV 2026',
                'codigo_cer' => 'CERT-2026-DV-010',
            ],
            [
                'paterno' => 'PALLUCA', 'materno' => 'CHUQUIMIA', 'nombres' => 'CRISTIANO RONALDO',
                'ci' => '10102280', 'cod_curso' => 'DV/2026', 'curso' => 'DESPACHADOR DE VUELO',
                'periodo' => '02/mar al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP DV 2026 CI 10102280 N° 011/26',
                'reg_estudios' => 'N° 011-DV 2026',
                'codigo_cer' => 'CERT-2026-DV-011',
            ],
            [
                'paterno' => 'QUISPE', 'materno' => 'HIDALGO', 'nombres' => 'GERMAN RICHARD',
                'ci' => '7023361', 'cod_curso' => 'DV/2026', 'curso' => 'DESPACHADOR DE VUELO',
                'periodo' => '02/mar al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP DV 2026 CI 7023361 N° 012/26',
                'reg_estudios' => 'N° 012-DV 2026',
                'codigo_cer' => 'CERT-2026-DV-012',
            ],

            // ==============================================================
            // 2. CURSO: TRIPULANTE DE CABINA (TCP / TCP II - 2026)
            // ==============================================================
            [
                'paterno' => 'ALIAGA', 'materno' => 'CONDORI', 'nombres' => 'KATERIN MILENA',
                'ci' => '11066284', 'cod_curso' => 'TCP/2026', 'curso' => 'TRIPULANTE DE CABINA',
                'periodo' => '02/mar al 30/oct 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'reg_diploma' => 'CIAC TAMEP TCP 2026 CI 11066284 N° 001/26',
                'reg_estudios' => 'N° 001-TCP 2026',
                'codigo_cer' => 'CERT-2026-TCP-001',
            ],
            [
                'paterno' => 'APAZA', 'materno' => 'ALCON', 'nombres' => 'DANNA VICTORIA',
                'ci' => '13215054', 'cod_curso' => 'TCP/2026', 'curso' => 'TRIPULANTE DE CABINA',
                'periodo' => '02/mar al 30/oct 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'reg_diploma' => 'CIAC TAMEP TCP 2026 CI 13215054 N° 002/26',
                'reg_estudios' => 'N° 002-TCP 2026',
                'codigo_cer' => 'CERT-2026-TCP-002',
            ],
            [
                'paterno' => 'CALLE', 'materno' => 'VERA', 'nombres' => 'MARIA CLARA',
                'ci' => '9894994', 'cod_curso' => 'TCP/2026', 'curso' => 'TRIPULANTE DE CABINA',
                'periodo' => '02/mar al 30/oct 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'reg_diploma' => 'CIAC TAMEP TCP 2026 CI 9894994 N° 003/26',
                'reg_estudios' => 'N° 003-TCP 2026',
                'codigo_cer' => 'CERT-2026-TCP-003',
            ],
            [
                'paterno' => 'FLORES', 'materno' => 'SOLIZ', 'nombres' => 'MARCO ANTONIO',
                'ci' => '10904454', 'cod_curso' => 'TCP/2026', 'curso' => 'TRIPULANTE DE CABINA',
                'periodo' => '02/mar al 30/oct 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'reg_diploma' => 'CIAC TAMEP TCP 2026 CI 10904454 N° 004/26',
                'reg_estudios' => 'N° 004-TCP 2026',
                'codigo_cer' => 'CERT-2026-TCP-004',
            ],
            [
                'paterno' => 'PACHECO', 'materno' => 'AZURDUY', 'nombres' => 'ALEJANDRA MELISSA',
                'ci' => '13087329', 'cod_curso' => 'TCP/2026', 'curso' => 'TRIPULANTE DE CABINA',
                'periodo' => '02/mar al 30/oct 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'reg_diploma' => 'CIAC TAMEP TCP 2026 CI 13087329 N° 005/26',
                'reg_estudios' => 'N° 005-TCP 2026',
                'codigo_cer' => 'CERT-2026-TCP-005',
            ],
            [
                'paterno' => 'VARGAS', 'materno' => 'SAICO', 'nombres' => 'ESTHER AIDA',
                'ci' => '14047426', 'cod_curso' => 'TCP/2026', 'curso' => 'TRIPULANTE DE CABINA',
                'periodo' => '02/mar al 30/oct 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'reg_diploma' => 'CIAC TAMEP TCP 2026 CI 14047426 N° 006/26',
                'reg_estudios' => 'N° 006-TCP 2026',
                'codigo_cer' => 'CERT-2026-TCP-006',
            ],
            [
                'paterno' => 'YUPANQUI', 'materno' => 'MACHACA', 'nombres' => 'MARISOL CARMEN',
                'ci' => '12635620', 'cod_curso' => 'TCP/2026', 'curso' => 'TRIPULANTE DE CABINA',
                'periodo' => '02/mar al 30/oct 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'reg_diploma' => 'CIAC TAMEP TCP 2026 CI 12635620 N° 007/26',
                'reg_estudios' => 'N° 007-TCP 2026',
                'codigo_cer' => 'CERT-2026-TCP-007',
            ],
            [
                'paterno' => 'ALEJO', 'materno' => 'CALDERON', 'nombres' => 'PAMELA',
                'ci' => '9944945', 'cod_curso' => 'TCP II/2026', 'curso' => 'TRIPULANTE DE CABINA',
                'periodo' => '08/jun al 04/dic 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'reg_diploma' => 'CIAC TAMEP TCP II 2026 CI 9944945 N° 008/26',
                'reg_estudios' => 'N° 008-TCP 2026',
                'codigo_cer' => 'CERT-2026-TCP-008',
            ],
            [
                'paterno' => 'CONDE', 'materno' => 'VELASCO', 'nombres' => 'PAOLA',
                'ci' => '6906775', 'cod_curso' => 'TCP II/2026', 'curso' => 'TRIPULANTE DE CABINA',
                'periodo' => '08/jun al 04/dic 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'reg_diploma' => 'CIAC TAMEP TCP II 2026 CI 6906775 N° 009/26',
                'reg_estudios' => 'N° 009-TCP 2026',
                'codigo_cer' => 'CERT-2026-TCP-009',
            ],
            [
                'paterno' => 'LISME', 'materno' => 'ARIAS', 'nombres' => 'ANAHI BELEN',
                'ci' => '16982877', 'cod_curso' => 'TCP II/2026', 'curso' => 'TRIPULANTE DE CABINA',
                'periodo' => '08/jun al 04/dic 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'reg_diploma' => 'CIAC TAMEP TCP II 2026 CI 16982877 N° 010/26',
                'reg_estudios' => 'N° 010-TCP 2026',
                'codigo_cer' => 'CERT-2026-TCP-010',
            ],
            [
                'paterno' => 'RAMOS', 'materno' => 'BLANCO', 'nombres' => 'GERALD MAURICIO',
                'ci' => '12895675', 'cod_curso' => 'TCP II/2026', 'curso' => 'TRIPULANTE DE CABINA',
                'periodo' => '08/jun al 04/dic 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'reg_diploma' => 'CIAC TAMEP TCP II 2026 CI 12895675 N° 011/26',
                'reg_estudios' => 'N° 011-TCP 2026',
                'codigo_cer' => 'CERT-2026-TCP-011',
            ],
            [
                'paterno' => 'SARCO', 'materno' => 'CABRERA', 'nombres' => 'KATIA ABRIL',
                'ci' => '9918174', 'cod_curso' => 'TCP II/2026', 'curso' => 'TRIPULANTE DE CABINA',
                'periodo' => '08/jun al 04/dic 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'reg_diploma' => 'CIAC TAMEP TCP II 2026 CI 9918174 N° 012/26',
                'reg_estudios' => 'N° 012-TCP 2026',
                'codigo_cer' => 'CERT-2026-TCP-012',
            ],
            [
                'paterno' => 'TUSCO', 'materno' => 'NISTAUZ', 'nombres' => 'DAYRA ISABEL',
                'ci' => '13605697', 'cod_curso' => 'TCP II/2026', 'curso' => 'TRIPULANTE DE CABINA',
                'periodo' => '08/jun al 04/dic 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'reg_diploma' => 'CIAC TAMEP TCP II 2026 CI 13605697 N° 013/26',
                'reg_estudios' => 'N° 013-TCP 2026',
                'codigo_cer' => 'CERT-2026-TCP-013',
            ],
            [
                'paterno' => 'VILA', 'materno' => 'BANEGAS', 'nombres' => 'SELENE JHERALDIN',
                'ci' => '13280133', 'cod_curso' => 'TCP II/2026', 'curso' => 'TRIPULANTE DE CABINA',
                'periodo' => '08/jun al 04/dic 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'reg_diploma' => 'CIAC TAMEP TCP II 2026 CI 13280133 N° 014/26',
                'reg_estudios' => 'N° 014-TCP 2026',
                'codigo_cer' => 'CERT-2026-TCP-014',
            ],

            // ==============================================================
            // 3. CURSO: AGENTE DE TRÁFICO (AGT/2026)
            // ==============================================================
            [
                'paterno' => 'APAZA', 'materno' => 'MAMANI', 'nombres' => 'LIZ LEYDE',
                'ci' => '13084026', 'cod_curso' => 'AGT/2026', 'curso' => 'AGENTE DE TRÁFICO',
                'periodo' => '31/ago al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'CERTIFICADO DE APROBACIÓN Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP AGT 2026 CI 13084026 Nº 001/26',
                'reg_estudios' => 'N° 001-AGT 2026',
                'codigo_cer' => 'CERT-2026-AGT-001',
            ],
            [
                'paterno' => 'BURGOA', 'materno' => 'ARRATIA', 'nombres' => 'VLADIMIR FELIX',
                'ci' => '12393586', 'cod_curso' => 'AGT/2026', 'curso' => 'AGENTE DE TRÁFICO',
                'periodo' => '31/ago al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'CERTIFICADO DE APROBACIÓN Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP AGT 2026 CI 12393586 Nº 002/26',
                'reg_estudios' => 'N° 002-AGT 2026',
                'codigo_cer' => 'CERT-2026-AGT-002',
            ],
            [
                'paterno' => 'CRUZ', 'materno' => 'ORAQUENI', 'nombres' => 'JUDITH',
                'ci' => '12731853', 'cod_curso' => 'AGT/2026', 'curso' => 'AGENTE DE TRÁFICO',
                'periodo' => '31/ago al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'CERTIFICADO DE APROBACIÓN Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP AGT 2026 CI 12731853 Nº 003/26',
                'reg_estudios' => 'N° 003-AGT 2026',
                'codigo_cer' => 'CERT-2026-AGT-003',
            ],
            [
                'paterno' => 'CRUZ', 'materno' => 'VILLCA', 'nombres' => 'JOEL',
                'ci' => '13736718', 'cod_curso' => 'AGT/2026', 'curso' => 'AGENTE DE TRÁFICO',
                'periodo' => '31/ago al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'CERTIFICADO DE APROBACIÓN Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP AGT 2026 CI 13736718 Nº 004/26',
                'reg_estudios' => 'N° 004-AGT 2026',
                'codigo_cer' => 'CERT-2026-AGT-004',
            ],
            [
                'paterno' => 'FLORES', 'materno' => 'ESPINOZA', 'nombres' => 'BISMAR RODRIGO',
                'ci' => '8436336', 'cod_curso' => 'AGT/2026', 'curso' => 'AGENTE DE TRÁFICO',
                'periodo' => '31/ago al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'CERTIFICADO DE APROBACIÓN Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP AGT 2026 CI 8436336 Nº 005/26',
                'reg_estudios' => 'N° 005-AGT 2026',
                'codigo_cer' => 'CERT-2026-AGT-005',
            ],
            [
                'paterno' => 'GAMEZ', 'materno' => 'TAPIA', 'nombres' => 'KAROL RASHELL',
                'ci' => '10698067', 'cod_curso' => 'AGT/2026', 'curso' => 'AGENTE DE TRÁFICO',
                'periodo' => '31/ago al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'CERTIFICADO DE APROBACIÓN Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP AGT 2026 CI 10698067 Nº 006/26',
                'reg_estudios' => 'N° 006-AGT 2026',
                'codigo_cer' => 'CERT-2026-AGT-006',
            ],
            [
                'paterno' => 'HUAYGUA', 'materno' => 'MONASTERIOS', 'nombres' => 'YHOSHELYN',
                'ci' => '9936571', 'cod_curso' => 'AGT/2026', 'curso' => 'AGENTE DE TRÁFICO',
                'periodo' => '31/ago al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'CERTIFICADO DE APROBACIÓN Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP AGT 2026 CI 9936571 Nº 007/26',
                'reg_estudios' => 'N° 007-AGT 2026',
                'codigo_cer' => 'CERT-2026-AGT-007',
            ],
            [
                'paterno' => 'LISME', 'materno' => 'ARIAS', 'nombres' => 'ANAHI BELEN',
                'ci' => '16982877', 'cod_curso' => 'AGT/2026', 'curso' => 'AGENTE DE TRÁFICO',
                'periodo' => '31/ago al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'CERTIFICADO DE APROBACIÓN Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP AGT 2026 CI 16982877 Nº 008/26',
                'reg_estudios' => 'N° 008-AGT 2026',
                'codigo_cer' => 'CERT-2026-AGT-008',
            ],
            [
                'paterno' => 'LLUSCO', 'materno' => 'TORREZ', 'nombres' => 'OSKAR ANDERSON',
                'ci' => '13020002', 'cod_curso' => 'AGT/2026', 'curso' => 'AGENTE DE TRÁFICO',
                'periodo' => '31/ago al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'CERTIFICADO DE APROBACIÓN Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP AGT 2026 CI 13020002 Nº 009/26',
                'reg_estudios' => 'N° 009-AGT 2026',
                'codigo_cer' => 'CERT-2026-AGT-009',
            ],
            [
                'paterno' => 'LOMA', 'materno' => 'MAQUERA', 'nombres' => 'ANGELES MILAGRO',
                'ci' => '13147920', 'cod_curso' => 'AGT/2026', 'curso' => 'AGENTE DE TRÁFICO',
                'periodo' => '31/ago al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'CERTIFICADO DE APROBACIÓN Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP AGT 2026 CI 13147920 Nº 010/26',
                'reg_estudios' => 'N° 010-AGT 2026',
                'codigo_cer' => 'CERT-2026-AGT-010',
            ],
            [
                'paterno' => 'PACHECO', 'materno' => 'AZURDUY', 'nombres' => 'ALEJANDRA MELISSA',
                'ci' => '13087329', 'cod_curso' => 'AGT/2026', 'curso' => 'AGENTE DE TRÁFICO',
                'periodo' => '31/ago al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'CERTIFICADO DE APROBACIÓN Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP AGT 2026 CI 13087329 Nº 011/26',
                'reg_estudios' => 'N° 011-AGT 2026',
                'codigo_cer' => 'CERT-2026-AGT-011',
            ],
            [
                'paterno' => 'PALLUCA', 'materno' => 'CHUQUIMIA', 'nombres' => 'CRISTIANO RONALDO',
                'ci' => '10102280', 'cod_curso' => 'AGT/2026', 'curso' => 'AGENTE DE TRÁFICO',
                'periodo' => '31/ago al 30/nov 2026', 'promedio' => '1', 'estado_acad' => 'APROBADO',
                'tipo_doc' => 'CERTIFICADO DE APROBACIÓN Y CERTIFICADO DE ESTUDIOS',
                'reg_diploma' => 'CIAC TAMEP AGT 2026 CI 10102280 Nº 012/26',
                'reg_estudios' => 'N° 012-AGT 2026',
                'codigo_cer' => 'CERT-2026-AGT-012',
            ],
        ];

        foreach ($datos as $item) {
            Certificado::updateOrCreate(
                ['codigo_cer' => $item['codigo_cer']],
                [
                    'nombre_per_cer' => $item['nombres'],
                    'apellido_paterno_cer' => $item['paterno'],
                    'apellido_materno_cer' => $item['materno'],
                    'apellido_per_cer' => trim("{$item['paterno']} {$item['materno']}"),
                    'carnet_per_cer' => $item['ci'],
                    'curso_cer' => $item['curso'],
                    'codigo_curso_cer' => $item['cod_curso'],
                    'periodo_gestion_cer' => $item['periodo'],
                    'promedio_final_cer' => $item['promedio'],
                    'estado_academico_cer' => $item['estado_acad'],
                    'tipo_documento_cer' => $item['tipo_doc'],
                    'registro_diploma_cer' => $item['reg_diploma'],
                    'registro_estudios_cer' => $item['reg_estudios'],
                    'docente_cer' => $docente,
                    'fecha_cer' => $fechaEmision,
                    'estado_cer' => 'activo',
                    'id_usu_1' => $adminId,
                ]
            );
        }

        $this->command->info('✅ 38 estudiantes y certificados CIAC TAMEP registrados correctamente.');
    }
}
