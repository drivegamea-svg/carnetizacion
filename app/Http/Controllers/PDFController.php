<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carnet;
use App\Http\Controllers\DateTime;
use TCPDF;
use TCPDF_FONTS;

class PDFController extends Controller
{

        // Agrega estas funciones aquí
    private function numeroAPalabras($numero) {
        $unidades = array(
            '', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'
        );
        
        $decenas = array(
            'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISÉIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE',
            'VEINTE', 'VEINTIUNO', 'VEINTIDÓS', 'VEINTITRÉS', 'VEINTICUATRO', 'VEINTICINCO', 'VEINTISÉIS', 'VEINTISIETE', 'VEINTIOCHO', 'VEINTINUEVE'
        );
        
        $centenas = array(
            '', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 
            'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'
        );
        
        $grupos = array('', 'MIL', 'MILLÓN', 'MILLONES', 'BILLÓN', 'BILLONES');
        
        $entero = intval($numero);
        $decimal = round(($numero - $entero) * 100);
        
        if ($entero == 0) {
            $texto_entero = 'CERO';
        } else {
            $texto_entero = $this->convertirGrupo($entero, $unidades, $decenas, $centenas, $grupos);
        }
        
        $texto_decimal = sprintf('%02d/100', $decimal);
        
        return $texto_entero . ' ' . $texto_decimal . ' BOLIVIANOS';
    }

    private function convertirGrupo($numero, $unidades, $decenas, $centenas, $grupos) {
        $texto = '';
        
        if ($numero >= 1000000) {
            $millones = floor($numero / 1000000);
            $texto .= $this->convertirGrupo($millones, $unidades, $decenas, $centenas, $grupos) . ' ';
            $texto .= ($millones == 1) ? 'MILLÓN ' : 'MILLONES ';
            $numero %= 1000000;
        }
        
        if ($numero >= 1000) {
            $miles = floor($numero / 1000);
            if ($miles == 1) {
                $texto .= 'MIL ';
            } else {
                $texto .= $this->convertirGrupo($miles, $unidades, $decenas, $centenas, $grupos) . ' MIL ';
            }
            $numero %= 1000;
        }
        
        if ($numero >= 100) {
            $cientos = floor($numero / 100);
            if ($cientos == 1 && $numero % 100 == 0) {
                $texto .= 'CIEN ';
            } else {
                $texto .= $centenas[$cientos] . ' ';
            }
            $numero %= 100;
        }
        
        if ($numero >= 10 && $numero <= 29) {
            $texto .= $decenas[$numero - 10] . ' ';
            $numero = 0;
        } else if ($numero >= 30) {
            $decena = floor($numero / 10);
            $texto .= ($decena == 2 ? 'VEINTI' : ($decena == 3 ? 'TREINTA' : 
                     ($decena == 4 ? 'CUARENTA' : ($decena == 5 ? 'CINCUENTA' :
                     ($decena == 6 ? 'SESENTA' : ($decena == 7 ? 'SETENTA' :
                     ($decena == 8 ? 'OCHENTA' : 'NOVENTA')))))));
            
            if ($numero % 10 != 0 && $decena != 2) {
                $texto .= ' Y ';
            }
            $numero %= 10;
        }
        
        if ($numero > 0) {
            $texto .= $unidades[$numero] . ' ';
        }
        
        return trim($texto);
    }

    private function formatearFecha($fecha) {
        if (empty($fecha)) {
            return '';
        }
        
        $fecha_objeto = new \DateTime($fecha);
        $formateador = new \IntlDateFormatter(
            'es_ES',
            \IntlDateFormatter::LONG,
            \IntlDateFormatter::NONE
        );
        
        return $formateador->format($fecha_objeto);
    }
    

    public function contrato($id)
    {
        $contrato = Carnet::findOrFail($id);

        // Carpeta de fuentes
        $fontDir = public_path('fonts') . DIRECTORY_SEPARATOR;
        if (!defined('K_PATH_FONTS')) {
            define('K_PATH_FONTS', $fontDir);
        }

        // Clase extendida con header/footer
        $pdf = new class('P', 'mm', [216, 330], true, 'UTF-8', false) extends TCPDF {
            public function Header()
            {
                $logo = public_path('pdf/HEADER.png');
                if (is_file($logo)) {
                    $this->Image($logo, 17, 5, 189);
                }
            }
            public function Footer()
            {
                $this->SetY(-15);
                $footerLogo = public_path('pdf/FOOTER.png');
                if (is_file($footerLogo)) {
                    $this->Image($footerLogo, 17, $this->GetY() - 2, 189);
                }
            }
        };

        // Configuración básica
        $pdf->SetTitle('Carnet Administrativo de Personal Eventual');
        $pdf->SetMargins(16.5, 41, 11);
        $pdf->SetAutoPageBreak(true, 25);
        $pdf->setFontSubsetting(true);
        $pdf->AddPage();

        $pdf->setCellHeightRatio(1.2); // menos espacio entre filas
        $pdf->SetCellPadding(0);       // sin padding interno extra

        // Registrar fuentes Calibri
        $family = 'calibri';
        $variants = [
            ''   => 'calibri-regular.ttf',
            'B'  => 'calibri-bold.ttf',
            'I'  => 'calibri-italic.ttf',
            'BI' => 'calibri-bold-italic.ttf',
        ];
        foreach ($variants as $style => $ttfFile) {
            $ttfPath = $fontDir . $ttfFile;
            if (is_file($ttfPath)) {
                $fontKey = TCPDF_FONTS::addTTFfont($ttfPath, 'TrueTypeUnicode', '', 96);
                if ($fontKey === false) {
                    $fontKey = pathinfo($ttfPath, PATHINFO_FILENAME);
                }
                $phpMetrics = $fontKey . '.php';
                if (is_file($fontDir . $phpMetrics)) {
                    $pdf->AddFont($family, $style, $phpMetrics);
                }
            }
        }

        // =========================
        // TEXTO DEL CONTRATO (BLOQUES)
        // =========================
        
        // TÍTULO
        $pdf->SetFont($family, 'B', 12);
        $pdf->MultiCell(0, 5, "CONTRATO ADMINISTRATIVO DE PERSONAL EVENTUAL", 0, 'C', 0, 1);

        $pdf->SetFont($family, 'B', 12);
        $pdf->MultiCell(0, 5, "(PARTIDA 12100)", 0, 'C', 0, 1);
        $pdf->Ln(2);
        $pdf->SetFont($family, 'B', 12);
        $pdf->MultiCell(0, 5, "D.T.H./F", 0, 'R', 0, 1);

        // Paso 1: Formatea el número de contrato para que tenga 3 dígitos
        $n_contrato_formateado = sprintf('%03d', $contrato->n_contrato);
        // Paso 2: Combina el número formateado con la gestión
        $n_contrato_completo = $n_contrato_formateado . '/' . $contrato->sigla_gestion;
        // Paso 3: Pasa el valor final a la función MultiCell
        $pdf->MultiCell(0, 5, $n_contrato_completo, 0, 'R', 0, 1);




        $fecha_ingreso_nota_contratacion_formateada = $this->formatearFecha($contrato->fecha_ingreso_nota_contratacion);

        $pdf->Ln(4);
        // DATOS DE FECHA
        $pdf->SetFont($family, '', 8.8);

        $html_intro = 'En fecha, <b>'.$fecha_ingreso_nota_contratacion_formateada.'</b>, el (la) <b>'.$contrato->cargo_autoridad_solicitante.'</b> mediante nota con CITE:<b>'.$contrato->cite_solicitud_contratacion.'</b>, hace conocer a la <b>XXXXXXXXXXXXXXXXXXXX</b>, la necesidad de contratar eventualmente personal para la  <b>XXXXXXXXXXXXXXXXXXXX</b>, en atención a la misma, se determina realizar el Carnet Administrativo de Personal Eventual bajo las siguientes clausulas:';

        $pdf->writeHTMLCell(0, 0, '', '', $html_intro, 0, 1, 0, true, 'J', true);
        $pdf->Ln(1);


        // CLÁUSULA PRIMERA - Versión con tabla
        $pdf->SetFont($family, '', 8.8);

        // Preparar los items de la lista
        $items_clausula1 = [
            'Gobierno Autónomo Municipal de El Alto en adelante denominado G.A.M.E.A., representado legalmente por <b>el Director de</b> Talento Humano dependiente de la Secretaria Municipal de Administración y Finanzas, <b>Abg. Ivan Puña Aguilar con C.I. 6013159 L.P. </b>autoridad delegado mediante Decreto Municipal Nº 082 de 30/05/2017, modificado mediante el Decreto Municipal N° 165 de 12/09/2022, para la suscripción y en su caso resolución de contratos administrativos con fuente de financiamiento de la Partida 12100 (Personal Eventual).',
            'Señor (a) <b>'.$contrato->apellido_paterno.' '.$contrato->apellido_materno.' '.$contrato->nombres.' con C.I. '.$contrato->ci.' '.$contrato->expedicion.'  </b>boliviano (a), mayor de edad, estado civil <b>'.$contrato->estado_civil.'</b>, (profesión u ocupación <b>'.$contrato->profesion.'</b>), con domicilio ubicado en la <b>'.$contrato->domicilio.'</b>, del municipio de <b>'.$contrato->ciudad.'</b> del Departamento de La Paz, hábil por derecho, en adelante denominado <b>"CONTRATADO (A)"</b>.'
        ];

        // Construir la tabla-lista con celdas separadas
        $tabla_lista_clausula1 = '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">';
        foreach ($items_clausula1 as $index => $item) {
            $numero = $index + 1;
            $tabla_lista_clausula1 .= <<<EOD
            <tr><td style="width: 10mm; text-align: right;"> <b>$numero.</b></td><td style="width: 3mm; text-align: right;"></td><td style="vertical-align: top; padding: 0; text-align: justify; width: 175mm;">$item</td></tr>
        EOD;
        }
        $tabla_lista_clausula1 .= '</table>';

        $html_clausula1 = <<<EOD
        <table style="text-align: justify;" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0;"><b>CLÁUSULA PRIMERA. - (De las partes).</b><br>Para efectos del presente contrato son partes:</td></tr><tr><td style="padding: 0; padding-top: 2mm;">$tabla_lista_clausula1</td></tr></table>
        EOD;

        $pdf->writeHTMLCell(0, 0, '', '', $html_clausula1, 0, 1, 0, true, '', true);
        $pdf->Ln(1);

        // CLÁUSULA SEGUNDA
        $pdf->SetFont($family, '', 8.8);
        $html_clausula2 = '<table style="text-align: justify" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding:0; margin:0;"><b>CLÁUSULA SEGUNDA. - (Del objeto).</b><br>La suscripción del presente contrato administrativo tiene por objeto satisfacer el requerimiento y la necesidad temporal de servicios del G.A.M.E.A., en el cargo de <b>'.$contrato->cargo.'</b>, para la <b>'.$contrato->dependencia.'</b> para cumplir los servicios de <b>“'.$contrato->funcion.'”</b>.</td>
            </tr>
        </table>';
        $pdf->writeHTMLCell(0, 0, '', '', $html_clausula2, 0, 1, 0, true, '', true);
        $pdf->Ln(1);

        // CLÁUSULA TERCERA
        $pdf->SetFont($family, '', 8.8);
        $items = [
            'Constitución Política del Estado Plurinacional de Bolivia de 7 de febrero de 2009.',
            'Ley Nro. 482 de Gobiernos Autónomos Municipales de 9 de enero de 2014.',
            'Ley 2027 de 27 de octubre de 1999, Ley Nro. 1178 de Administración y Control Gubernamentales de 20 de Julio de 1990 y su Reglamento por la Función Pública Decreto Supremo Nro. 23318-A de 3 de noviembre de 1992.',
            'Ley Nro. 1613 LEY DEL PRESUPUESTO GENERAL DEL ESTADO GESTION 2025, Decreto Supremo 5301 de fecha 02 de enero de 2025.',
            'Ley 004 (Ley de Lucha contra la Corrupción, Enriquecimiento Ilícito e Investigación de fortunas "Marcelo Quiroga Santa Cruz").',
            'Ley Nro. 065 de Pensiones de 10 de diciembre de 2010, sus modificaciones y Código de Seguridad Social.',
            'Ley Nro. 031 Marco de Autonomías y Descentralización "Andrés Ibáñez" de 19 de julio de 2010.',
            'Resolución Ministerial Nro. 308 de 25 de junio de 2024 que Aprueba los Clasificadores Presupuestarios para la Gestión 2025.',
            'Reglamento Interno de Personal del Órgano Ejecutivo del G.A.M.E.A.',
            'Otras disposiciones vigentes aplicables al Personal Eventual.'
        ];
        // Construir la tabla-lista con celdas separadas
        $tabla_lista = '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">';
        foreach ($items as $index => $item) {
            $numero = $index + 1;
            $tabla_lista .= <<<EOD
            <tr><td style="width: 10mm;text-align: rigth;"><b>$numero.</b></td><td style="width: 3mm;text-align: rigth;"></td><td style="vertical-align: top; padding: 0; text-align: justify;width:175mm;"><b>$item</b></td></tr>
        EOD;
        }
        $tabla_lista .= '</table>';

        $html_clausula3 = <<<EOD
        <table style="text-align: justify;" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding: 0;" style="text-align: justify;"><b>CLÁUSULA TERCERA. - (De la naturaleza del contrato y de la normativa aplicable).</b><br>El presente contrato es de naturaleza administrativa, por lo que no admite reconocimiento de ningún tipo de beneficio social, ni genera una relación de tipo laboral con el G.A.M.E.A., sujetándose a una supervisión y la aplicación de la siguiente normativa legal:</td>
            </tr>
            <tr>
                <td style="padding: 0; padding-top: 2mm;">$tabla_lista</td>
            </tr>
            <tr>
            <td>Bajo ninguna circunstancia se podrá considerar el presente contrato como consultoría, y considerándose que no son personal permanente no se encuentran sujetos a lo establecido por la Ley General del Trabajo.</td>
            </tr>
        </table>
        EOD;

        $pdf->writeHTMLCell(0, 0, '', '', $html_clausula3, 0, 1, 0, true, '', true);
        $pdf->Ln(1);


        // CLÁUSULA CUARTA
        $pdf->SetFont($family, '', 8.8);
        $html_clausula4 = <<<EOD
        <table style="text-align: justify;" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding: 0;"><b>CLÁUSULA CUARTA. - (De los servicios).</b><br>El (la) <b>CONTRATADO</b> (A) deberá prestar los siguientes servicios de <b>$contrato->funcion</b>.</td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTMLCell(0, 0, '', '', $html_clausula4, 0, 1, 0, true, '', true);
        $pdf->Ln(1);
     
        // CLÁUSULA QUINTA
        $salario_formateado = number_format($contrato->salario_basico, 2, ',', '.');
        $salario_en_palabras = $this->numeroAPalabras($contrato->salario_basico);
        $pdf->SetFont($family, '', 8.8);
        $html_clausula5 = <<<EOD
        <table style="text-align: justify;" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding: 0;"><b>CLÁUSULA QUINTA. - (Del pago de servicio).</b><br>La remuneración de los servicios prestados a personas sujetas a contrato en forma transitoria o eventual, considera para el efecto, la equivalencia  de funciones, escala salarial y clasificador presupuestario vigente; el servicio prestado por el (la) <b>CONTRATADO (A)</b> será pagado al mes vencido, en moneda nacional y de curso legal, conforme al nivel salarial y cuadro de equivalencias vigente del G.A.M.E.A., sujeto a los descuentos y/o retenciones establecidos por ley, mismo que corresponderá a la suma de Bs. <b>$salario_formateado.- ($salario_en_palabras)</b>, al efecto el (la) <b>CONTRATADO (A)</b> elevará a su inmediato superior informe escrito sobre el cumplimiento de las actividades realizadas a la conclusión del contrato.</td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTMLCell(0, 0, '', '', $html_clausula5, 0, 1, 0, true, '', true);
        $pdf->Ln(1);

        // CLÁUSULA SEXTA
        $fecha_inicio_formateada = $this->formatearFecha($contrato->fecha_inicio);
        $fecha_fin_formateada = $this->formatearFecha($contrato->fecha_final);

        $pdf->SetFont($family, '', 8.8);
        $html_clausula6 = <<<EOD
        <table style="text-align: justify;" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding: 0;"><b>CLÁUSULA SEXTA. - (De la duración y vigencia del contrato).</b><br>El presente contrato administrativo de personal eventual (Partida 12100), tendrá vigencia a partir del <b>$fecha_inicio_formateada</b> hasta el <b>$fecha_fin_formateada</b>, a cuya culminación cesaran sus efectos sin necesidad de comunicación escrita.</td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTMLCell(0, 0, '', '', $html_clausula6, 0, 1, 0, true, '', true);
        $pdf->Ln(1);

        // CLÁUSULA SÉPTIMA
        $pdf->SetFont($family, '', 8.8);
        $html_clausula7 = <<<EOD
        <table style="text-align: justify;" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding: 0;"><b>CLÁUSULA SÉPTIMA. - (Del lugar de servicios).</b><br>El <b>CONTRATADO (A)</b> prestara sus servicios en la <b>UNIDAD DE SELECCIÓN Y CONTRATACION de la DIRECCION DE TALENTO HUMANO  dependiente de la SECRETARIA MUNICIPAL DE ADMINISTRACION Y FINANZAS</b> del G.A.M.E.A., bajo supervisión del inmediato superior. Sin embargo, el G.A.M.E.A. podrá cambiar el destino del lugar de prestación de servicios, según necesidades y requerimiento de la Entidad, en el marco del presente <b>Carnet</b>.</td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTMLCell(0, 0, '', '', $html_clausula7, 0, 1, 0, true, '', true);
        $pdf->Ln(1);

        // CLÁUSULA OCTAVA
        $pdf->SetFont($family, '', 8.8);
        $html_clausula8 = <<<EOD
        <table style="text-align: justify;" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding: 0;"><b>CLÁUSULA OCTAVA. - (Del horario de servicio).</b><br>El (la) <b>CONTRATADO (A)</b> desempeñará sus actividades en horarios establecidos por el G.A.M.E.A., en horario de atención de la entidad, que será comunicado a la suscripción del presente contrato.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTMLCell(0, 0, '', '', $html_clausula8, 0, 1, 0, true, '', true);
        $pdf->Ln(1);

        // CLÁUSULA NOVENA
        $pdf->SetFont($family, '', 8.8);

        // Preparar los items de la lista para derechos del CONTRATADO
        $items_derechos_contratado = [
            'Recibir el pago por los servicios prestados.',
            'A gozar de seguridad social junto a sus dependientes declarados; la afiliación estará a cargo del G.A.M.E.A.',
            'A recibir todos los insumos y/o instrumentos necesarios para la ejecución del objeto de este contrato.'
        ];

        // Construir la tabla-lista para derechos del CONTRATADO con sangría adicional
        $tabla_derechos_contratado = '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">';
        foreach ($items_derechos_contratado as $index => $item) {
            $numero = $index + 1;
            $tabla_derechos_contratado .= <<<EOD
            <tr>
                <td style="width: 7mm; text-align: right;"></td>
                <td style="width: 3mm; text-align: right;"></td>
                <td style="width: 5mm; text-align: right;"><b>$numero</b>.</td>
                <td style="width: 3mm; text-align: right;"></td>
                <td style="vertical-align: top; padding: 0; text-align: justify; width: 170mm;">$item</td>
            </tr>
        EOD;
        }
        $tabla_derechos_contratado .= '</table>';

        // Preparar los items de la lista para derechos del G.A.M.E.A.
        $items_derechos_gamea = [
            'A exigir cumplimiento de los servicios prestados por el (la) <b>CONTRATADO (A)</b>, bajo los principios de eficacia y eficiencia previstos en la ley, las disposiciones del presente contrato y de las regulaciones internas del G.A.M.E.A.'
        ];

        // Construir la tabla-lista para derechos del G.A.M.E.A. con sangría adicional
        $tabla_derechos_gamea = '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">';
        foreach ($items_derechos_gamea as $index => $item) {
            $numero = $index + 1;
            $tabla_derechos_gamea .= <<<EOD
            <tr>
                <td style="width: 7mm; text-align: right;"></td>
                <td style="width: 3mm; text-align: right;"></td>
                <td style="width: 5mm; text-align: right;"><b>$numero</b>.</td>
                <td style="width: 3mm; text-align: right;"></td>
                <td style="vertical-align: top; padding: 0; text-align: justify; width: 170mm;">$item</td>
            </tr>
        EOD;
        }
        $tabla_derechos_gamea .= '</table>';

        $html_clausula9 = <<<EOD
        <table style="text-align: justify;" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding: 0;"><b>CLÁUSULA NOVENA. - (De los derechos de las partes).</b></td>
            </tr>
            <tr>
                <td style="padding: 0; padding-top: 2mm;"><table cellpadding="0" cellspacing="0" border="0" style="width: 100%;"><tr><td style="width: 10mm; text-align: right; vertical-align: top;"><b>I.</b></td><td style="width: 3mm; text-align: right;"></td><td style="vertical-align: top; padding: 0; text-align: justify;"><b>Son derechos del CONTRATADO:</b></td></tr> </table></td>
            </tr>
            <tr>
                <td style="padding: 0;">$tabla_derechos_contratado</td>
            </tr>
            <tr>
                <td style="padding: 0; padding-top: 2mm;"><table cellpadding="0" cellspacing="0" border="0" style="width: 100%;"><tr><td style="width: 10mm; text-align: right; vertical-align: top;"><b>II.</b></td><td style="width: 3mm; text-align: right;"></td><td style="vertical-align: top; padding: 0; text-align: justify;"><b>Son derechos del G.A.M.E.A.</b></td></tr></table></td>
            </tr>
            <tr>
                <td style="padding: 0;">$tabla_derechos_gamea</td>
            </tr>
        </table>
        EOD;

        $pdf->writeHTMLCell(0, 0, '', '', $html_clausula9, 0, 1, 0, true, '', true);
        $pdf->Ln(1);



        // CLÁUSULA DÉCIMA
        $pdf->SetFont($family, '', 8.8);

        // Preparar los items de la lista para obligaciones del CONTRATADO
        $items_obligaciones_contratado = [
            'Asistir y cumplir puntualmente el horario de ingreso y salida en el G.A.M.E.A., durante la vigencia del contrato, caso contrario se aplicarán las sanciones estipuladas al efecto en el Reglamento Interno del Personal (R.I.P.).',
            'Conocer el contenido y el alcance del Reglamento Interno de Personal del Órgano Ejecutivo del G.A.M.E.A.',
            'Cuidar y mantener en buen estado cualquier activo, instrumento o maquinaria que le sea asignado para la prestación del servicio objeto de este contrato.',
            'Una vez finalizada la vigencia del contrato, la documentación y los bienes los cuales se encontraba bajo su custodia deberán ser entregados a su inmediato superior debidamente foliado y bajo inventario a la unidad de Activos Fijos en el plazo máximo de 48 horas, bajo responsabilidad administrativa.',
            'Presentar todos los documentos que requiera el G.A.M.E.A. tanto para alcanzar los efectos de este contrato como para la afiliación en la seguridad social, dentro del plazo que establezca el G.A.M.E.A.',
            'Presentar informe de los servicios realizados a la conclusión del contrato, a su inmediato superior.',
            'Cumplir las funciones establecidas en la solicitud de su contratación.',
            'Durante y después de la prestación de sus servicios, no deberá realizar el mal o inadecuado uso de la información y/ documentos del G.A.M.E.A. a los que tenga acceso.',
            'A la conclusión de la vigencia de contrato, recopilar, clasificar, y dejar en orden la información física y digital para su respectiva transferencia al inmediato superior o al sucesor en el cargo.'
        ];

        // Construir la tabla-lista para obligaciones del CONTRATADO con sangría adicional
        $tabla_obligaciones_contratado = '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">';
        foreach ($items_obligaciones_contratado as $index => $item) {
            $numero = $index + 1;
            $tabla_obligaciones_contratado .= <<<EOD
            <tr>
                <td style="width: 7mm; text-align: right;"></td>
                <td style="width: 3mm; text-align: right;"></td>
                <td style="width: 5mm; text-align: right;"><b>$numero</b>.</td>
                <td style="width: 3mm; text-align: right;"></td>
                <td style="vertical-align: top; padding: 0; text-align: justify; width: 170mm;">$item</td>
            </tr>
        EOD;
        }
        $tabla_obligaciones_contratado .= '</table>';

        // Preparar los items de la lista para obligaciones del G.A.M.E.A.
        $items_obligaciones_gamea = [
            'Hacer un seguimiento continuo al <b>CONTRATADO (A)</b>, para el cumplimiento del objeto del presente contrato, a través del inmediato superior.',
            'Pagar el servicio prestado por el (la) <b>CONTRATADO (A)</b>.',
            'Hacer conocer el contenido del Reglamento Interno de Personal del Órgano Ejecutivo del G.A.M.E.A. al <b>CONTRATADO (A)</b>.',
            'Realizar los trámites necesarios para la afiliación del <b>CONTRATADO (A)</b> y dependientes declarados ante el ente de seguridad social.'
        ];

        // Construir la tabla-lista para obligaciones del G.A.M.E.A. con sangría adicional
        $tabla_obligaciones_gamea = '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">';
        foreach ($items_obligaciones_gamea as $index => $item) {
            $numero = $index + 1;
            $tabla_obligaciones_gamea .= <<<EOD
            <tr>
                <td style="width: 7mm; text-align: right;"></td>
                <td style="width: 3mm; text-align: right;"></td>
                <td style="width: 5mm; text-align: right;"><b>$numero</b>.</td>
                <td style="width: 3mm; text-align: right;"></td>
                <td style="vertical-align: top; padding: 0; text-align: justify; width: 170mm;">$item</td>
            </tr>
        EOD;
        }
        $tabla_obligaciones_gamea .= '</table>';

        $html_clausula10 = <<<EOD
        <table style="text-align: justify;" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding: 0;"><b>CLÁUSULA DÉCIMA. - (De las obligaciones de las partes).</b></td>
            </tr>
            <tr>
                <td style="padding: 0; padding-top: 2mm;"><table cellpadding="0" cellspacing="0" border="0" style="width: 100%;"><tr><td style="width: 10mm; text-align: right; vertical-align: top;"><b>I.</b></td><td style="width: 3mm; text-align: right;"></td><td style="vertical-align: top; padding: 0; text-align: justify;"><b>Son obligaciones del CONTRATADO:</b></td></tr></table></td>
            </tr>
            <tr>
                <td style="padding: 0;">$tabla_obligaciones_contratado</td>
            </tr>
            <tr>
                <td style="padding: 0; padding-top: 2mm;"><table cellpadding="0" cellspacing="0" border="0" style="width: 100%;"><tr><td style="width: 10mm; text-align: right; vertical-align: top;"><b>II.</b></td><td style="width: 3mm; text-align: right;"></td><td style="vertical-align: top; padding: 0; text-align: justify;"><b>Son obligaciones del G.A.M.E.A.:</b></td></tr></table></td>
            </tr>
            <tr>
                <td style="padding: 0;">$tabla_obligaciones_gamea</td>
            </tr>
        </table>
        EOD;

        $pdf->writeHTMLCell(0, 0, '', '', $html_clausula10, 0, 1, 0, true, '', true);
        $pdf->Ln(1);

        // CLÁUSULA DECIMA PRIMERA
        $pdf->SetFont($family, '', 8.8);
        $html_clausula11 = <<<EOD
        <table style="text-align: justify;" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding: 0;"><b>CLÁUSULA DECIMA PRIMERA. - (confidencialidad).</b><br>los materiales producidos por el/la <b>CONTRATADO (A)</b>, así como la información a la que este tuviere acceso, durante o después de la ejecución del presente contrato, tendrá carácter confidencial, quedando expresamente prohibida su divulgación a terceros, exceptuando los casos en que la ENTIDAD emita un pronunciamiento escrito estableciendo lo contrario. <br>Toda información, documentación y "herramientas digitales" generadas por el/la <b>CONTRATADO (A)</b> en el desempeño de sus actividades, serán de propiedad del Gobierno Autónomo Municipal de EL Alto.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTMLCell(0, 0, '', '', $html_clausula11, 0, 1, 0, true, '', true);
        $pdf->Ln(1);

        // CLÁUSULA DÉCIMA SEGUNDA
        $pdf->SetFont($family, '', 8.8);
        $html_clausula12 = <<<EOD
        <table style="text-align: justify;" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding: 0;"><b>CLÁUSULA DÉCIMA SEGUNDA. - (De la terminación del contrato).</b><br>El presente contrato se considerará finalizado al cumplimiento del plazo estipulado en la cláusula sexta, sin necesidad de comunicación escrita a él o la CONTRATADO (A), no operando la tacita reconducción.</td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTMLCell(0, 0, '', '', $html_clausula12, 0, 1, 0, true, '', true);
        $pdf->Ln(1);

        // CLÁUSULA DÉCIMA TERCERA
        $pdf->SetFont($family, '', 8.8);

        // Preparar los items de la lista para causas atribuibles al CONTRATADO
        $items_causas_contratado = [
            'Por incumplimiento del <b>CONTRATO</b> atribuible a él o la <b>CONTRATADO (A)</b>.',
            'Por informe negativo y/o insatisfactorio del inmediato superior con relación a lo estipulado en la Cláusula Decima Párrafo I punto 1, 3, 5, 6 y 7 que impida el cumplimiento del objeto del contrato.',
            'Por incurrir en alguna falta prevista en el Reglamento Interno de Personal del Órgano Ejecutivo del G.A.M.E.A.',
            'Por inasistencia injustificada por el plazo establecido por Ley.',
            'Petición escrita del <b>CONTRATADO (A)</b>.'
        ];

        // Construir la tabla-lista para causas atribuibles al CONTRATADO
        $tabla_causas_contratado = '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">';
        foreach ($items_causas_contratado as $index => $item) {
            $letra = chr(97 + $index); // Genera a, b, c, ...
            $tabla_causas_contratado .= <<<EOD
            <tr>
                <td style="width: 8mm; text-align: right;"><b>$letra</b>)</td>
                <td style="width: 3mm; text-align: right;"></td>
                <td style="vertical-align: top; padding: 0; text-align: justify; width: 177mm;">$item</td>
            </tr>
        EOD;
        }
        $tabla_causas_contratado .= '</table>';

        // Preparar los items de la lista para causas atribuibles a la ENTIDAD
        $items_causas_entidad = [
            'Por incumplimiento en el pago.'
        ];

        // Construir la tabla-lista para causas atribuibles a la ENTIDAD
        $tabla_causas_entidad = '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">';
        foreach ($items_causas_entidad as $index => $item) {
            $letra = chr(97 + $index); // Genera a, b, c, ...
            $tabla_causas_entidad .= <<<EOD
            <tr>
                <td style="width: 8mm; text-align: right;">$letra)</td>
                <td style="width: 3mm; text-align: right;"></td>
                <td style="vertical-align: top; padding: 0; text-align: justify; width: 177mm;">$item</td>
            </tr>
        EOD;
        }
        $tabla_causas_entidad .= '</table>';

        $html_clausula13 = <<<EOD
        <table style="text-align: justify;" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding: 0;"><b>CLÁUSULA DÉCIMA TERCERA. - (De la resolución del contrato).</b><br>El presente Carnet Administrativo de Personal Eventual (Partida 12100), será resuelto:</td>
            </tr>
            <tr>
                <td style="padding: 0; padding-top: 2mm;"><b>2.1 CAUSAS ATRIBUIBLES AL CONTRATADO (A)</b></td>
            </tr>
            <tr>
                <td style="padding: 0;">$tabla_causas_contratado</td>
            </tr>
            <tr>
                <td style="padding: 0; padding-top: 2mm;"><b>2.2 CAUSAS ATRIBUIBLES A LA ENTIDAD</b></td>
            </tr>
            <tr>
                <td style="padding: 0;">$tabla_causas_entidad</td>
            </tr>
            <tr>
                <td style="padding: 0; padding-top: 2mm;"><b>2.3 POR FUERZA MAYOR O CASO FORTUITO</b></td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTMLCell(0, 0, '', '', $html_clausula13, 0, 1, 0, true, '', true);
        $pdf->Ln(1);

        // CLÁUSULA DÉCIMA CUARTA
        $pdf->SetFont($family, '', 8.8);
        $html_clausula14 = <<<EOD
        <table style="text-align: justify;" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding: 0;"><b>CLÁUSULA DÉCIMA CUARTA. - (De la aceptación).</b><br>Para su estricto cumplimiento y en señal de conformidad a todas y cada una de las cláusulas precedentes, las partes firman, en cinco ejemplares de un mismo tenor en la ciudad de <b>El Alto, a los 8 días del mes de Enero de 2025.</b></td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTMLCell(0, 0, '', '', $html_clausula14, 0, 1, 0, true, '', true);
        $pdf->Ln(18);



        // =========================
        // FIRMA
        // =========================
        $pdf->SetFont($family, 'B', 8.8);
        $pdf->MultiCell(100, 6, "………………………………………………………………………………\nSr(a). JUAN JOSE FERNANDO ZABALETA LAUCA\nC.I. 9887654 cod. QR", 0, 'L', 0, 1);
        $pdf->Ln(2);
        $pdf->SetFont($family, '', 8.8);
        $pdf->MultiCell(100, 6, "c.c Arch\nc.c. Enc. Planillas\nc.c. Unidad de Registro", 0, 'L', 0, 1);

        // Salida final
        $fileName = "CONTRATO_{$contrato->id}.pdf";
        $pdfContent = $pdf->Output($fileName, 'S');

        return response()->make($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'public',
        ]);
    }
}