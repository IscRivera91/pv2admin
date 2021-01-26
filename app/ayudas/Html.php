<?php

namespace App\ayudas;

use App\ayudas\Redireccion;


class Html
{
    public static function inputHidden(
        string $name,
        string $value
    ) :string {
        
        $inputHiddenHtml = "<input  name='$name'  value='$value' type='hidden'>";
        
        return $inputHiddenHtml;
    }

    public static function inputText(
        int    $col,
        string $label,
        string $id,
        string $name,
        string $placeholder = '',
        string $value       = '',
        bool   $saltarLinea = false
    ) :string {
        $placeholder = self::obtenerPlaceholder($label,$placeholder);
        $inputTextHtml = '';
        $inputTextHtml .= self::generaPrincipioInput($col);
        $inputTextHtml .= "<input title='$label' id='$id' name='$name' placeholder='$placeholder' value='$value' class='form-control  form-control-sm' type='text'>";
        $inputTextHtml .= self::generaFinalInput($saltarLinea);
        
        return $inputTextHtml;
    }

    public static function inputTextRequired(
        int    $col,
        string $label,
        string $id,
        string $name,
        string $placeholder = '',
        string $value       = '',
        bool   $saltarLinea = false
    ) :string {
        $placeholder = self::obtenerPlaceholder($label,$placeholder);
        $inputTextRequiredHtml = '';
        $inputTextRequiredHtml .= self::generaPrincipioInput($col);
        $inputTextRequiredHtml .= "<input title='$label' id='$id' name='$name' placeholder='$placeholder' value='$value' required class='form-control  form-control-sm' type='text'>";
        $inputTextRequiredHtml .= self::generaFinalInput($saltarLinea);
        
        return $inputTextRequiredHtml;
    }

    public static function inputDate(
        int    $col,
        string $label,
        string $id,
        string $name,
        string $placeholder = '',
        string $value       = '',
        bool   $saltarLinea = false
    ) :string {
        $placeholder = self::obtenerPlaceholder($label,$placeholder);
        $inputDateHtml = '';
        $inputDateHtml .= self::generaPrincipioInput($col);
        $inputDateHtml .= "<input title='$label' id='$id' name='$name' placeholder='$placeholder' value='$value' class='form-control  form-control-sm' type='date'>";
        $inputDateHtml .= self::generaFinalInput($saltarLinea);
        
        return $inputDateHtml;
    }

    public static function inputDateRequired(
        int    $col,
        string $label,
        string $id,
        string $name,
        string $placeholder = '',
        string $value       = '',
        bool   $saltarLinea = false
    ) :string {
        $placeholder = self::obtenerPlaceholder($label,$placeholder);
        $inputDateHtml = '';
        $inputDateHtml .= self::generaPrincipioInput($col);
        $inputDateHtml .= "<input title='$label' id='$id' name='$name' placeholder='$placeholder' value='$value' required class='form-control  form-control-sm' type='date'>";
        $inputDateHtml .= self::generaFinalInput($saltarLinea);
        
        return $inputDateHtml;
    }

    public static function inputNumber(
        int    $col,
        string $label,
        string $id,
        string $name,
        string $placeholder = '',
        string $value       = '',
        bool   $saltarLinea = false
    ) :string {
        $placeholder = self::obtenerPlaceholder($label,$placeholder);
        $inputNumbertHtml = '';
        $inputNumbertHtml .= self::generaPrincipioInput($col);
        $inputNumbertHtml .= "<input title='$label' id='$id' name='$name' placeholder='$placeholder' value='$value' class='form-control  form-control-sm' type='number'>";
        $inputNumbertHtml .= self::generaFinalInput($saltarLinea);
        
        return $inputNumbertHtml;
    }

    public static function inputNumberRequired(
        int    $col,
        string $label,
        string $id,
        string $name,
        string $placeholder = '',
        string $value       = '',
        bool   $saltarLinea = false
    ) :string {
        $placeholder = self::obtenerPlaceholder($label,$placeholder);
        $inputNumberRequiredHtml = '';
        $inputNumberRequiredHtml .= self::generaPrincipioInput($col);
        $inputNumberRequiredHtml .= "<input title='$label' id='$id' name='$name' placeholder='$placeholder' value='$value' required class='form-control  form-control-sm' type='number'>";
        $inputNumberRequiredHtml .= self::generaFinalInput($saltarLinea);
        
        return $inputNumberRequiredHtml;
    }

    public static function inputFloat(
        int    $col,
        string $label,
        string $id,
        string $name,
        string $placeholder = '',
        string $value       = '',
        bool   $saltarLinea = false
    ) :string {
        $placeholder = self::obtenerPlaceholder($label,$placeholder);
        $inputNumbertHtml = '';
        $inputNumbertHtml .= self::generaPrincipioInput($col);
        $inputNumbertHtml .= "<input title='$label' id='$id' name='$name' placeholder='$placeholder' value='$value' class='form-control  form-control-sm' type='number' step='any'>";
        $inputNumbertHtml .= self::generaFinalInput($saltarLinea);
        
        return $inputNumbertHtml;
    }

    public static function inputFloatRequired(
        int    $col,
        string $label,
        string $id,
        string $name,
        string $placeholder = '',
        string $value       = '',
        bool   $saltarLinea = false
    ) :string {
        $placeholder = self::obtenerPlaceholder($label,$placeholder);
        $inputNumbertHtml = '';
        $inputNumbertHtml .= self::generaPrincipioInput($col);
        $inputNumbertHtml .= "<input title='$label' id='$id' name='$name' placeholder='$placeholder' value='$value' required class='form-control  form-control-sm' type='number' step='any'>";
        $inputNumbertHtml .= self::generaFinalInput($saltarLinea);
        
        return $inputNumbertHtml;
    }

    public static function inputPassword(
        int    $col,
        string $label,
        string $id,
        string $name,
        string $placeholder = '',
        string $value       = '',
        bool   $saltarLinea = false
    ) :string {
        $placeholder = self::obtenerPlaceholder($label,$placeholder);
        $inputPasswordHtml = '';
        $inputPasswordHtml .= self::generaPrincipioInput($col);
        $inputPasswordHtml .= "<input title='$label' id='$id' name='$name' placeholder='$placeholder' value='$value' required class='form-control  form-control-sm' type='password'>";
        $inputPasswordHtml .= self::generaFinalInput($saltarLinea);
        
        return $inputPasswordHtml;
    }

    private static function generaPrincipioInput(int $col):string
    {
        $principioInputHtml = '';
        $principioInputHtml .= "<div class=col-md-$col>";
        $principioInputHtml .= "<div class='input-group mb-3'>";

        return $principioInputHtml;
    }

    private static function generaFinalInput(bool $saltarLinea):string
    {
        $finalInputHtml = '';
        $finalInputHtml .= "</div>";
        $finalInputHtml .= "</div>";
        
        if ($saltarLinea) {
            $finalInputHtml .= "<div class='col-md-12'></div>";
        }
      
        return $finalInputHtml;
    }

    private static function obtenerPlaceholder(string $label, string $placeholder):string
    {
        if ($placeholder == '') {
            $placeholder = $label;
        }
        return $placeholder;
    }

    public static function selectConBuscador(
        string $nombreCampoId, 
        string $label, 
        string $name, 
        int    $col, 
        array  $registros   = array(),
        string $elementos   = '', 
        string $value       = '-1', 
        int    $select2Id   = 1, 
        string $required    = 'required',
        string $chart       = ' ', 
        bool   $saltarLinea = false
    ) :string {
        $selectHtml = '';
        $selectHtml .= self::generaPrincipioSelect($col,$label);
        
        $selectHtml .= "<select title='$label' $required name='$name' class='form-control form-control-sm select2'";
        $selectHtml .= " data-placeholder='$label'  data-select2-id='$select2Id' tabindex='-1' >";

        $selectHtml .= self::generaSelectOptions($nombreCampoId, $registros, $elementos, $value, $chart);
        $selectHtml .= self::generaFinalSelect($saltarLinea);
        return $selectHtml;
    }

    public static function selectMultiple(
        string $nombreCampoId,
        string $label,
        string $name,
        int    $col,
        array  $registros   = array(),
        string $elementos   = '',
        array  $value       = array(),
        int    $select2Id   = 1,
        string $required    = 'required',
        string $chart       = ' ',
        bool   $saltarLinea = false
    ) :string {
        $selectHtml = '';
        $selectHtml .= self::generaPrincipioSelect($col,$label);

        $selectHtml .= "<select title='$label' $required name='{$name}[]' class='form-control form-control-sm select2'";
        $selectHtml .= " multiple='multiple' data-placeholder='$label'  data-select2-id='$select2Id' tabindex='-1' >";

        $elementosArray = explode(',',$elementos);

        $selectHtml .= "<option hidden ></option>";

        foreach ($registros as $registro) {

            $valorRegistroId = $registro[$nombreCampoId];

            $textValueOption = '';

            foreach ($elementosArray as $elemento){
                $textValueOption .= $registro[$elemento].$chart;
            }
            $textValueOption = trim($textValueOption,$chart);

            $selected = '';
            if (in_array($valorRegistroId,$value)) {
                $selected = "selected='true'";
            }
                
            $selectHtml .= "<option $selected value='$valorRegistroId'>$textValueOption</option>";
        }

        
        $selectHtml .= self::generaFinalSelect($saltarLinea);
        return $selectHtml;
    }

    private static function generaSelectOptions(string $nombreCampoId, array $registros, string $elementos, string $value, string $chart):string
    {
        $elementosArray = explode(',',$elementos);

        $optionsGenerados = "<option hidden ></option>";

        foreach ($registros as $registro) {

            $valorRegistroId = $registro[$nombreCampoId];

            $textValueOption = '';

            foreach ($elementosArray as $elemento){
                $textValueOption .= $registro[$elemento].$chart;
            }
            $textValueOption = trim($textValueOption,$chart);

            $selected = '';
            if ($value == $valorRegistroId){
                $selected = "selected='true'";
            }
                
            $optionsGenerados .= "<option $selected value='$valorRegistroId'>$textValueOption</option>";
        }
        return $optionsGenerados;
    }

    private static function generaPrincipioSelect(int $col, string $label):string
    {
        $principioSelectGenerado = '';
        $principioSelectGenerado .= "<div class=col-md-$col>";
        $principioSelectGenerado .= "<div class='input-group input-group-sm mb-3'>";
        $principioSelectGenerado .= "<div class='input-group-prepend'>";
        $principioSelectGenerado .= "<label id='inputGroup-sizing-sm' class='input-group-text'>".strtolower($label)."</label>";
        $principioSelectGenerado .= "</div>";
        return $principioSelectGenerado;
    }

    private static function generaFinalSelect(bool $saltarLinea):string
    {
        $finalSelectGenerado = '';
        $finalSelectGenerado .= "</select>";
        $finalSelectGenerado .= "</div>";
        $finalSelectGenerado .= "</div>";
        if ($saltarLinea) {
            $finalSelectGenerado .= "<div class='col-md-12'></div>";
        }
        return $finalSelectGenerado;
    }

    public static function selectActivo(
        string $label,
        string $name,
        int    $col,
        string $value       = '-1',
        int    $select2Id   = 1,
        string $required    = 'required',
        string $chart       = ' ',
        bool   $saltarLinea = false
    ) :string {

        $registros = [
            ["id" => 1, 'texto' => TEXTO_REGISTRO_ACTIVO],
            ["id" => 0, 'texto' => TEXTO_REGISTRO_INACTIVO]
        ];

        return self::selectConBuscador('id', $label, $name, $col, $registros, 'texto', $value, $select2Id, $required, $chart, $saltarLinea);
    }

    public static function submit(string $label, string $name, int $col, bool $saltarLinea = true):string
    {
        $submitHtml = '';
        if ($saltarLinea) {
            $submitHtml = "<div class='col-md-12'></div>";
        }
        $submitHtml .= "<div class=col-md-$col>";
        $submitHtml .= "<div class='form-group'>";
        $submitHtml .= "<button type='submit' name='$name' class='btn btn-default btn-argus btn-block  btn-flat btn-sm'>$label</button>";
        $submitHtml .= "</div>";
        $submitHtml .= "</div>";

        return $submitHtml;
    }

    public static function linkBoton(string $urlDestino, string $label, int $col, bool $saltarLinea = false):string
    {
        $linkBotonHtml = '';
        if ($saltarLinea) {
            $linkBotonHtml .= "<div class='col-md-12'></div>";
        }
        $linkBotonHtml .= "<div class=col-md-$col>";
        $linkBotonHtml .= "<div class='form-group'>";
        $linkBotonHtml .= "<a class='btn btn-default btn-argus btn-block  btn-flat btn-sm' href='$urlDestino'>$label</a>";
        $linkBotonHtml .= "</div>";
        $linkBotonHtml .= "</div>";

        return $linkBotonHtml;
    }

    public static function paginador(int $numeroDePaginas, int $pagina, string $tabla):string
    {
        $urlBase = Redireccion::obtener($tabla,'lista',SESSION_ID).'&pag=';

        $liClass = 'page-item';
        $aClas = 'page-link';
        
        $paginadorHtml = '';
        $paginadorHtml .= "<br><nav aria-label='navigation'>"; // inicia <nav>
        $paginadorHtml .= "<ul class='pagination'>"; // inicia <ul>

        // inicia el <li> de el boton pagina anterior
        $paginadorHtml .= "<li class='$liClass' >";
        $paginaAnterior = (int)$pagina-1;
        $href = '';
        if ($pagina > 1) { $href = "href='{$urlBase}{$paginaAnterior}'"; }
        $paginadorHtml .= "<a class='$aClas'  $href aria-label='Anterior'>";
        $paginadorHtml .= "<span aria-hidden='true'>&laquo;</span>";
        $paginadorHtml .= "</a>";
        $paginadorHtml .= "</li>";
        // termina el <li> de el boton pagina anterior

        for ($i = 1 ; $i <= $numeroDePaginas ; $i++) {
            $active = '';
            if ($i == $pagina){ $active = 'active'; }
            $paginadorHtml .= "<li class='$active $liClass' ><a class='$aClas'  href='".$urlBase.$i."'>$i</a></li>";
        }

        // inicia el <li> de el boton pagina siguiente
        $paginadorHtml .= "<li class='$liClass' >";
        $paginaSiguiente = (int)$pagina+1;
        $href = '';
        if ($pagina < $numeroDePaginas) { $href = "href='{$urlBase}{$paginaSiguiente}'"; }
        $paginadorHtml .= "<a class='$aClas'  $href aria-label='Anterior'>";
        $paginadorHtml .= "<span aria-hidden='true'>&raquo;</span>";
        $paginadorHtml .= "</a>";
        $paginadorHtml .= "</li>";
        // termina el <li> de el boton pagina siguiente

        $paginadorHtml .= "</ul>"; // termina <ul>
        $paginadorHtml .= "</nav>"; // termina <nav>
        return $paginadorHtml;
    }

}