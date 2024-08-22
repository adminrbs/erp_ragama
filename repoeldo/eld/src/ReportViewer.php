<?php

namespace RepoEldo\ELD;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ReportViewer
{
    protected $group_count = 0;
    protected $parameters = [];
    protected $group_sub_total = array();
    protected $group_grand_total = array();
    protected $group_table_count = 0;
    protected $page_size = 0;




    public function viewReport($report)
    {

        return $this->createPage($report);
    }


    public function addParameter($parameter_name, $parameter_value)
    {
        $this->parameters[$parameter_name] = $parameter_value;
    }



    private function createPage($url)
    {
        ini_set('memory_limit', '512M');

        $file = File::get("jsonreport/" . $url);
        $json = json_decode($file, true);
        if (isset($json['title'])) {
            $title = $json['title'];
        }

        $header = [];
        if (isset($json['header'])) {
            $header = $json['header'];
        }


        $footer = [];
        if (isset($json['footer'])) {
            $footer = $json['footer'];
        }


        $detail = [];
        if (isset($json['detail'])) {
            $detail = $json['detail'];
        }


        $column_header = [];
        if (isset($json['column-header'])) {
            $column_header = $json['column-header'];
        }




        $title_height = $this->getBandHeight($title);
        $header_height = $this->getBandHeight($header);
        $column_header_height = $this->getBandHeight($column_header);
        $footer_height = $this->getBandHeight($footer);


        $page_top = $title_height + $header_height + $column_header_height;
        $title_top = -$page_top;
        $header_top = $title_top + $title_height;
        $column_header_top = $header_top + $column_header_height;
        $footer_bottom = -$footer_height;
        //dd($title_top);

        $title_content = "";
        foreach ($title as $property) {
            if (isset($property['label'])) {
                $title_content .= $this->createLabel($property['label']);
            }
            if (isset($property['table'])) {
                $title_content .= $this->createTable($property['table']);
            }
            if (isset($property['date'])) {
                $title_content .= $this->createDate($property['date']);
            }
            if (isset($property['img'])) {
                if (extension_loaded('gd')) {
                    $title_content .= $this->createImg($property['img']);
                } else {
                    dd("GD extension is NOT installed.");
                }
            }
            if (isset($property['number'])) {
                $title_content .= $this->createNumber($property['number']);
            }
        }


        $header_content = "";
        foreach ($header as $property) {
            if (isset($property['label'])) {
                $header_content .= $this->createLabel($property['label']);
            }
            if (isset($property['date'])) {
                $header_content .= $this->createDate($property['date']);
            }
            if (isset($property['table'])) {
                $header_content .= $this->createTable($property['table']);
            }
            if (isset($property['number'])) {
                $header_content .= $this->createNumber($property['number']);
            }
        }

        $column_header_content = "";
        foreach ($column_header as $property) {
            if (isset($property['label'])) {
                $column_header_content .= $this->createLabel($property['label']);
            }
            if (isset($property['date'])) {
                $column_header_content .= $this->createDate($property['date']);
            }
            if (isset($property['table'])) {
                $column_header_content .= $this->createTable($property['table']);
            }
            if (isset($property['number'])) {
                $column_header_content .= $this->createNumber($property['number']);
            }
        }

        $footer_content = "";
        foreach ($footer as $property) {
            //dd($property['label']);
            if (isset($property['label'])) {
                // $footer_content .= $this->createLabel($property['label']);
            }
            if (isset($property['table'])) {
                $footer_content .= $this->createTable($property['table']);
            }
        }

        $detail_content = "";
        foreach ($detail as $property) {
            if (isset($property['label'])) {
                $detail_content .= $this->createLabel($property['label']);
            }
            //dump($property['table']);
            if (isset($property['table'])) {
                $detail_content .= $this->createTable($property['table']);
            }
            if (isset($property['group'])) {
                $detail_content .= $this->createGroup($property['group']);
                $detail_content .= $this->createGrandTotal($property['group']);
                $detail_content .= $this->createGroupFooter($property['group']);
            }
            if (isset($property['date'])) {
                $detail_content .= $this->createDate($property['date']);
            }
            if (isset($property['number'])) {
                $detail_content .= $this->createNumber($property['number']);
            }
        }
        $oriantation = $this->getOriantation($json);
        $paper = $this->getPaper($json);
        $page = view('eldo_report::eldo_report', compact('title_top', 'header_top', 'footer_bottom', 'title_height', 'header_height', 'footer_height', 'column_header_height', 'column_header_top', 'page_top', 'title_content', 'header_content', 'footer_content', 'detail_content', 'column_header_content'));


        //$pdf = App::make('dompdf.wrapper');
        //$pdf->loadHTML($page)->setPaper($paper, $oriantation);
        return $page;
    }



    private function createDate($obj)
    {

        $text = "";
        if (isset($obj['text'])) {
            $text = $obj['text'];
            if (count(explode('@_$', $text)) > 1) {
                $text = $this->parameters[explode('@_$', $text)[1]];
            }
        }

        $align = "left";
        if (isset($obj['align'])) {
            $align = $obj['align'];
        }

        $border_top = "0";
        if (isset($obj['border-top'])) {
            $border_top = $obj['border-top'];
        }

        $border_bottom = "0";
        if (isset($obj['border-bottom'])) {
            $border_bottom = $obj['border-bottom'];
        }

        $border_left = "0";
        if (isset($obj['border-left'])) {
            $border_left = $obj['border-left'];
        }

        $border_right = "0";
        if (isset($obj['border-right'])) {
            $border_right = $obj['border-right'];
        }

        $bgcolor = "#ffffff";
        if (isset($obj['bg-color'])) {
            $bgcolor = $obj['bg-color'];
        }


        $color = "#000000";
        if (isset($obj['color'])) {
            $color = $obj['color'];
        }

        $width = "100%";
        if (isset($obj['width'])) {
            $width = $obj['width'];
        }

        $height = "30";
        if (isset($obj['height'])) {
            $height = $obj['height'];
        }

        $position = "";
        if (isset($obj['position'])) {
            $position = $obj['position'];
        }

        $x = "0";
        if (isset($obj['x'])) {
            $x = $obj['x'];
        }

        $y = "0";
        if (isset($obj['y'])) {
            $y = $obj['y'];
        }

        $opacity = "1";
        if (isset($obj['opacity'])) {
            $opacity = $obj['opacity'];
        }

        $border_color = "#000000";
        if (isset($obj['border-color'])) {
            $border_color = $obj['border-color'];
        }

        $padding_top = "0";
        if (isset($obj['padding-top'])) {
            $padding_top = $obj['padding-top'];
        }

        $font_size = "13";
        $font_style = "";
        $font_name = "";
        if (isset($obj['font'])) {

            $font = $obj['font'];
            if (isset($font["size"])) {
                $font_size = $font["size"];
            }
            if (isset($font["style"])) {
                $font_style = $font["style"];
            }
            if (isset($font["name"])) {
                $font_name = $font["name"];
            }
        }


        if (isset($obj['format'])) {

            $format = $obj['format'];

            if ($text == "") {
                $text = Carbon::now()->toDateString();
            }
            $format1 = explode("/", $format);
            $format2 = explode("-", $format);
            $text_format1 = explode("/", $text);
            $text_format2 = explode("-", $text);

            $date_array = ["yyyy", "mm", "dd"];

            if (count($text_format1) == 3) {
                if (strlen($text_format1[0]) == 2) {
                    $date_array[0] = $text_format1[2];
                    $date_array[1] = $text_format1[1];
                    $date_array[2] = $text_format1[0];
                } else if (strlen($text_format1[0]) == 4) {
                    $date_array[0] = $text_format1[0];
                    $date_array[1] = $text_format1[1];
                    $date_array[2] = $text_format1[2];
                }
            } else if (count($text_format2) == 3) {
                if (strlen($text_format2[0]) == 2) {
                    $date_array[0] = $text_format2[2];
                    $date_array[1] = $text_format2[1];
                    $date_array[2] = $text_format2[0];
                } else if (strlen($text_format2[0]) == 4) {
                    $date_array[0] = $text_format2[0];
                    $date_array[1] = $text_format2[1];
                    $date_array[2] = $text_format2[2];
                }
            }



            if (count($format1) == 3) {

                if ($format1[0] == "yyyy" && $format1[1] == "mm" && $format1[2] == "dd") {
                    $text = $date_array[0] . "/" . $date_array[1] . "/" . $date_array[2];
                } else if ($format1[0] == "dd" && $format1[1] == "mm" && $format1[2] == "yyyy") {
                    $text = $date_array[2] . "/" . $date_array[1] . "/" . $date_array[0];
                }
            } else if (count($format2) == 3) {

                if ($format2[0] == "yyyy" && $format2[1] == "mm" && $format2[2] == "dd") {
                    $text = $date_array[0] . "-" . $date_array[1] . "-" . $date_array[2];
                } else if ($format2[0] == "dd" && $format2[1] == "mm" && $format2[2] == "yyyy") {
                    $text = $date_array[2] . "-" . $date_array[1] . "-" . $date_array[0];
                }
            }
        }




        $style = "text-align:" . $align . ";";
        $style .= "border-top:" . $border_top . "px solid " . $border_color . ";";
        $style .= "border-bottom:" . $border_bottom . "px solid " . $border_color . ";";
        $style .= "border-right:" . $border_right . "px solid " . $border_color . ";";
        $style .= "border-left:" . $border_left . "px solid " . $border_color . ";";
        $style .= "background-color:" . $bgcolor . ";";
        $style .= "color:" . $color . ";";
        $style .= "width:" . $width . "px;";
        $style .= "height:" . $height . "px;";
        $style .= "position: " . $position . ";";
        $style .= "top:" . $y . "px;";
        $style .= "left:" . $x . "px;";
        $style .= "opacity:" . $opacity . "";
        $style .= "padding-top:" . $padding_top . "px;";
        //$style .= "font-family:" . $font_name . ";";
        $style .= "font-size:" . $font_size . "px;";
        $style .= "font-weight:" . $font_style . ";";


        $label = '<div style="' . $style . '">';
        $label .= $text;
        $label .= '</div>';
        return $label;
    }

    private function createLabel($obj)
    {

        $text = "";
        if (isset($obj['text'])) {
            $text = $obj['text'];
            if (count(explode('@_$', $text)) > 1) {
                $text = $this->parameters[explode('@_$', $text)[1]];
            }
        }

        $align = "left";
        if (isset($obj['align'])) {
            $align = $obj['align'];
        }

        $border_top = "0";
        if (isset($obj['border-top'])) {
            $border_top = $obj['border-top'];
        }

        $border_bottom = "0";
        if (isset($obj['border-bottom'])) {
            $border_bottom = $obj['border-bottom'];
        }

        $border_left = "0";
        if (isset($obj['border-left'])) {
            $border_left = $obj['border-left'];
        }

        $border_right = "0";
        if (isset($obj['border-right'])) {
            $border_right = $obj['border-right'];
        }

        $bgcolor = "#ffffff";
        if (isset($obj['bg-color'])) {
            $bgcolor = $obj['bg-color'];
        }


        $color = "#000000";
        if (isset($obj['color'])) {
            $color = $obj['color'];
        }

        $width = "100%";
        if (isset($obj['width'])) {
            $width = $obj['width'];
        }

        $height = "30";
        if (isset($obj['height'])) {
            $height = $obj['height'];
            if (count(explode('@_$', $height)) > 1) {
                $height = $this->parameters[explode('@_$', $height)[1]];
            }
        }

        $position = "";
        if (isset($obj['position'])) {
            $position = $obj['position'];
        }

        $x = "0";
        if (isset($obj['x'])) {
            $x = $obj['x'];
        }

        $y = "0";
        if (isset($obj['y'])) {
            $y = $obj['y'];
        }

        $opacity = "1";
        if (isset($obj['opacity'])) {
            $opacity = $obj['opacity'];
        }

        $border_color = "#000000";
        if (isset($obj['border-color'])) {
            $border_color = $obj['border-color'];
        }

        $padding_top = "0";
        if (isset($obj['padding-top'])) {
            $padding_top = $obj['padding-top'];
        }

        $font_size = "13";
        $font_style = "";
        $font_name = "";
        if (isset($obj['font'])) {

            $font = $obj['font'];
            if (isset($font["size"])) {
                $font_size = $font["size"];
            }
            if (isset($font["style"])) {
                $font_style = $font["style"];
            }
            if (isset($font["name"])) {
                $font_name = $font["name"];
            }
        }




        $style = "text-align:" . $align . ";";
        $style .= "border-top:" . $border_top . "px solid " . $border_color . ";";
        $style .= "border-bottom:" . $border_bottom . "px solid " . $border_color . ";";
        $style .= "border-right:" . $border_right . "px solid " . $border_color . ";";
        $style .= "border-left:" . $border_left . "px solid " . $border_color . ";";
        $style .= "background-color:" . $bgcolor . ";";
        $style .= "color:" . $color . ";";
        $style .= "width:" . $width . "px;";
        $style .= "height:" . $height . "px;";
        $style .= "position: " . $position . ";";
        $style .= "top:" . $y . "px;";
        $style .= "left:" . $x . "px;";
        $style .= "opacity:" . $opacity . "";
        $style .= "padding-top:" . $padding_top . "px;";
        //$style .= "font-family:" . $font_name . ";";
        $style .= "font-size:" . $font_size . "px;";
        $style .= "font-weight:" . $font_style . ";";


        $label = '<div style="' . $style . '">';
        $label .= $text;
        $label .= '</div>';
        return $label;
    }



    private function createLabelArgs($obj, $args)
    {

        $text = "";
        if (isset($obj['text'])) {
            $text = $obj['text'];
            if (count(explode('@_$', $text)) > 1) {
                $text = $this->parameters[explode('@_$', $text)[1]];
            }
        }

        $align = "left";
        if (isset($obj['align'])) {
            $align = $obj['align'];
        }

        $border_top = "0";
        if (isset($obj['border-top'])) {
            $border_top = $obj['border-top'];
        }

        $border_bottom = "0";
        if (isset($obj['border-bottom'])) {
            $border_bottom = $obj['border-bottom'];
        }

        $border_left = "0";
        if (isset($obj['border-left'])) {
            $border_left = $obj['border-left'];
        }

        $border_right = "0";
        if (isset($obj['border-right'])) {
            $border_right = $obj['border-right'];
        }

        $bgcolor = "#ffffff";
        if (isset($obj['bg-color'])) {
            $bgcolor = $obj['bg-color'];
        }


        $color = "#000000";
        if (isset($obj['color'])) {
            $color = $obj['color'];
        }

        $width = "100%";
        if (isset($obj['width'])) {
            $width = $obj['width'];
        }

        $height = "30";
        if (isset($obj['height'])) {
            $height = $obj['height'];
        }

        $position = "";
        if (isset($obj['position'])) {
            $position = $obj['position'];
        }

        $x = "0";
        if (isset($obj['x'])) {
            $x = $obj['x'];
        }

        $y = "0";
        if (isset($obj['y'])) {
            $y = $obj['y'];
        }

        $opacity = "1";
        if (isset($obj['opacity'])) {
            $opacity = $obj['opacity'];
        }

        $border_color = "#000000";
        if (isset($obj['border-color'])) {
            $border_color = $obj['border-color'];
        }

        $padding_top = "0";
        if (isset($obj['padding-top'])) {
            $padding_top = $obj['padding-top'];
        }

        $font_size = "12";
        $font_style = "";
        $font_name = "";
        if (isset($obj['font'])) {

            $font = $obj['font'];
            if (isset($font["size"])) {
                $font_size = $font["size"];
            }
            if (isset($font["style"])) {
                $font_style = $font["style"];
            }
            if (isset($font["name"])) {
                $font_name = $font["name"];
            }
        }


        $style = "text-align:" . $align . ";";
        $style .= "border-top:" . $border_top . "px solid " . $border_color . ";";
        $style .= "border-bottom:" . $border_bottom . "px solid " . $border_color . ";";
        $style .= "border-right:" . $border_right . "px solid " . $border_color . ";";
        $style .= "border-left:" . $border_left . "px solid " . $border_color . ";";
        $style .= "background-color:" . $bgcolor . ";";
        $style .= "color:" . $color . ";";
        $style .= "width:" . $width . "px;";
        $style .= "height:" . $height . "px;";
        $style .= "position: " . $position . ";";
        $style .= "top:" . $y . "px;";
        $style .= "left:" . $x . "px;";
        $style .= "opacity:" . $opacity . "";
        $style .= "padding-top:" . $padding_top . "px;";
        //$style .= "font-family:" . $font_name . ";";
        $style .= "font-size:" . $font_size . "px;";
        $style .= "font-weight:" . $font_style . ";";


        $label = '<div style="' . $style . '">';
        $label .= $text . $args;
        $label .= '</div>';
        return $label;
    }




    private function createTable($obj)
    {



        $header = [];
        if (isset($obj['header'])) {
            $header = $obj['header'];
        }

        $body = [];
        if (isset($obj['body'])) {
            $body = $obj['body'];

            if (!is_array($body)) {
                if (count(explode('@_$', $body)) > 1) {
                    $body = $this->parameters[explode('@_$', $body)[1]];
                    $body = json_decode(json_encode($body), true);
                }
            }
        }







        $table = '<table style="width:100%"><tbody>';
        $table .= $this->createTableHeader($header);


        $sub_total = array();
        for ($i = 0; $i < count($body); $i++) {
            $table .= $this->createTableRow($header, $body[$i]);

            $sum = [];
            if (isset($obj['sum'])) {
                $sum = $obj['sum'];
            }



            $text = "";
            for ($i2 = 0; $i2 < count($header); $i2++) {
                if (isset($body[$i][array_keys($body[$i])[$i2]])) {

                    $text = $body[$i][array_keys($body[$i])[$i2]];

                    foreach ($sum as $sum_col) {
                        if ($sum_col == $header[$i2][0]["text"]) {
                            if (isset($sub_total[$sum_col])) {
                                $sub_total[$sum_col] += $text;
                            } else {
                                $sub_total[$sum_col] = $text;
                            }
                        }
                    }
                }
            }
        }

        array_push($this->group_sub_total, $sub_total);
        $table .= '</tbody></table>';

        if (isset($obj['sum'])) {
            $table .= $this->createTableSum($obj, $this->group_table_count);
        }
        $this->group_table_count++;
        return $table;
    }

    private function createTableHeader($header)
    {

        $thead = '<thead>';
        $thead .= '<tr>';
        for ($i = 0; $i < count($header); $i++) {
            $display = "";
            if (isset($header[$i][0]["visible"])) {
                if (!$header[$i][0]["visible"]) {
                    $display = "none";
                }
            }

            $font_size = 13;
            if (isset($header[$i][0]["font-size"])) {
                $font_size = $header[$i][0]["font-size"];
            }

            $thead .= '<th style="width:' . $header[$i][0]["width"] . 'px;display:' . $display . ';font-size:' . $font_size . 'px;text-align:' . $header[$i][0]["align"] . ';">';
            $thead .= '<div style="width:' . $header[$i][0]["width"] . 'px;display:' . $display . ';font-size:' . $font_size . 'px;text-align:' . $header[$i][0]["align"] . ';white-space: nowrap;overflow: hidden;text-overflow: clip;">';
            $thead .= $header[$i][0]["text"];
            $thead .= '</div>';
            $thead .= '</th>';
        }
        $thead .= '</tr>';
        $thead .= '</thead>';
        return $thead;
    }



    private function createTableRow($header, $body)
    {

        $tr = '<tr>';
        for ($i = 0; $i < count($header); $i++) {
            $display = "";
            if (isset($header[$i][0]["visible"])) {
                if (!$header[$i][0]["visible"]) {
                    $display = "none";
                }
            }

            $font_size = 13;
            if (isset($header[$i][0]["font-size"])) {
                $font_size = $header[$i][0]["font-size"];
            }



            $text = "";
            if (isset($body[array_keys($body)[$i]])) {
                $text = $body[array_keys($body)[$i]];
            }

            $format = "";
            if (isset($header[$i][0]["format"])) {
                $format = $header[$i][0]["format"];
            }

            if ($format == "number") {
                if (is_numeric($text)) {
                    $text =  number_format($text, 2);
                }
            }

            if ($format == "text") {
                if (is_numeric($text)) {
                    $text =  (int)$text;
                }
            }



            $tr .= '<td style="width:' . $header[$i][0]["width"] . 'px;display:' . $display . ';font-size:' . $font_size . 'px;text-align:' . $header[$i][0]["align"] . ';">';
            $tr .= '<div style="width:' . $header[$i][0]["width"] . 'px;display:' . $display . ';font-size:' . $font_size . 'px;text-align:' . $header[$i][0]["align"] . ';white-space: nowrap;overflow: hidden;text-overflow: clip;">';
            $tr .= $text;
            $tr .= '</div>';
            $tr .= '</td>';
        }
        $tr .= '</tr>';
        return $tr;
    }



    private function createImg($obj)
    {

        $url = "";
        if (isset($obj['url'])) {
            $url = $obj['url'];
            if (count(explode('@_$', $url)) > 1) {
                $url = str_replace('/media/', '../storage/app/', $this->parameters[explode('@_$', $url)[1]]);
            } else {
                $url = str_replace('/media/', '../storage/app/', $obj['url']);
            }
        }
        //dd($url);

        $width = "";
        if (isset($obj['width'])) {
            $width = $obj['width'];
        }

        $height = "";
        if (isset($obj['height'])) {
            $height = $obj['height'];
        }

        $x = "";
        if (isset($obj['x'])) {
            $x = $obj['x'];
        }

        $y = "";
        if (isset($obj['y'])) {
            $y = $obj['y'];
        }

        $position = "";
        if (isset($obj['position'])) {
            $position = $obj['position'];
        }

        $style = "width:" . $width . "px;";
        $style .= "height:" . $height . "px;";
        $style .= "top:" . $y . "px;";
        $style .= "left:" . $x . "px;";
        $style .= "position: " . $position . ";";

        $img = '<img src="' . $url . '" style="' . $style . '">';
        //dd($img);
        return $img;
    }


    private function getBandHeight($band)
    {
        foreach ($band as $property) {
            if (isset($property['height'])) {
                return $property['height'];
            }
        }
        return 0;
    }


    private function getOriantation($page)
    {
        $oriantation = "PORTRAIT";
        if (isset($page['oriantation'])) {
            $oriantation = $page['oriantation'];
        }
        return $oriantation;
    }


    private function getPaper($page)
    {
        $paper = "A4";
        if (isset($page['paper'])) {
            $paper = $page['paper'];
        }
        return $paper;
    }


    private function createGroup($obj)
    {


        $header = [];
        if (isset($obj['header'])) {
            $header = $obj['header'];
        }

        $details = [];
        if (isset($obj['detail'])) {
            $details = $obj['detail'];
        }






        $group_content = "";


        $footer = [];
        if (isset($obj['footer'])) {
            $footer = $obj['footer'];
        }





        foreach ($details as $detail) {

            if (isset($detail['table'])) {
                $table = $detail['table'];
                if (isset($table['body'])) {
                    $body = $table['body'];
                    if (count(explode('@_$', $body)) > 1) {
                        $body = $this->parameters[explode('@_$', $body)[1]];
                        $group_data = json_decode(json_encode($body), true);
                        foreach ($group_data as $data) {
                            foreach ($header as $head) {
                                $group_content .=  $this->createLabel($head['label']);
                            }
                            $table_id = 0;
                            foreach ($data as $dd) {
                                $group_content .= "";

                                if (isset($detail['sub-title'])) {
                                    $sub_title = $detail['sub-title'];
                                    if (isset($sub_title['title'])) {
                                        if (is_array($sub_title['title'])) {
                                            if (isset($sub_title['label'])) {
                                                if (count($sub_title['title']) == 1) {
                                                    $array = $this->parameters[explode('@_$', $sub_title['title'][0])[1]];
                                                    if (isset($array[$table_id])) {
                                                        $group_content .=  $this->createLabelArgs($sub_title['label'], $array[$table_id]);
                                                    }
                                                    $table_id += 1;
                                                }
                                            }
                                        } else {
                                            if (isset($sub_title['label'])) {
                                                $group_content .=  $this->createLabelArgs($sub_title['label'], $sub_title['title']);
                                            }
                                        }
                                    } else {
                                        if (isset($sub_title['label'])) {
                                            $group_content .=  $this->createLabel($sub_title['label']);
                                        }
                                    }
                                }


                                $table["body"] = $dd;
                                $group_content .= $this->createTable($table);
                                $group_content .= '<div  style="white-space: nowrap;overflow: hidden;text-overflow: clip;padding:0px;">...............................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................</div>';
                            }
                        }
                    }
                }
            }
        }

        return $group_content;
    }



    private function createTableSum($obj, $id)
    {

        $header = [];
        if (isset($obj['header'])) {
            $header = $obj['header'];
        }

        $sum = [];
        if (isset($obj['sum'])) {
            $sum = $obj['sum'];
        }





        $table = '<table style="width:100%">';
        $table .= '<tr>';
        $isSetSubTotalText = false;
        for ($i = 0; $i < count($header); $i++) {
            $display = "";
            $sum_text = "";
            $font_size = 13;
            if (isset($header[$i][0]["font-size"])) {
                $font_size = $header[$i][0]["font-size"];
            }

            if (isset($header[$i][0]["visible"])) {
                if (!$header[$i][0]["visible"]) {
                    $display = "none";
                }
            } else {
                if (!$isSetSubTotalText) {
                    $sum_text = '<div  style="white-space: nowrap;overflow: hidden;text-overflow: clip;font-size:' . $font_size . 'px;">Sub Total : </div>';
                }
                $isSetSubTotalText = true;
            }


            //dd($this->group_sub_total);
            foreach ($sum as $sum_col) {
                if ($sum_col == $header[$i][0]["text"]) {
                    if (isset($this->group_sub_total[$id][$sum_col])) {
                        $sum_text = $this->group_sub_total[$id][$sum_col];
                        if (isset($this->group_grand_total[$sum_col])) {
                            $this->group_grand_total[$sum_col] += $sum_text;
                        } else {
                            $this->group_grand_total[$sum_col] = $sum_text;
                        }
                    }
                }
            }
            if (is_numeric($sum_text)) {
                $sum_text = number_format($sum_text, 2);
            }

            $table .= '<th style="width:' . $header[$i][0]["width"] . 'px;display:' . $display . ';font-size:' . $font_size . 'px;text-align:' . $header[$i][0]["align"] . ';">';
            $table .= '<div style="width:' . $header[$i][0]["width"] . 'px;display:' . $display . ';font-size:' . $font_size . 'px;text-align:' . $header[$i][0]["align"] . ';white-space: nowrap;overflow: hidden;text-overflow: clip;">';
            $table .= $sum_text;
            $table .= '</div>';
            $table .= '</th>';
        }
        $table .= '</tr>';
        $table .= '</table>';
        return $table;
    }

    private function createGroupFooter($obj)
    {
        $footer = [];
        if (isset($obj['footer'])) {
            $footer = $obj['footer'];
        }


        $label = "";
        if (isset($footer[0]['label'])) {
            $label =  $this->createLabel($footer[0]['label']);
        }

        return $label;
    }


    private function createGrandTotal($detail_array)
    {

        $details = [];
        if (isset($detail_array['detail'])) {
            $details = $detail_array['detail'];
        }


        $obj = [];
        if (isset($details[0]['table'])) {
            $obj = $details[0]['table'];
        }


        $header = [];
        if (isset($obj['header'])) {
            $header = $obj['header'];
        }

        $sum = [];
        if (isset($obj['sum'])) {
            $sum = $obj['sum'];
        }


        $grand_total = [];
        if (isset($obj['grand-total'])) {
            $grand_total = $obj['grand-total'];
        }



        $visible_grand_total = "";
        if (is_array($grand_total)) {
            if (isset($grand_total["visible"])) {
                if (!$grand_total["visible"]) {
                    $visible_grand_total = "display:none;";
                }
            }
        }



        $table = '<table style="' . $visible_grand_total . 'width:100%;">';
        $table .= '<tr>';
        $isSetSubTotalText = false;
        for ($i = 0; $i < count($header); $i++) {
            $display = "";
            $sum_text = "";
            $font_size = 13;
            if (isset($header[$i][0]["font-size"])) {
                $font_size = $header[$i][0]["font-size"];
            }

            if (isset($header[$i][0]["visible"])) {
                if (!$header[$i][0]["visible"]) {
                    $display = "none";
                }
            } else {
                if (!$isSetSubTotalText) {
                    $sum_text = '<div  style="white-space: nowrap;overflow: hidden;text-overflow: clip;font-size:' . $font_size . 'px">Grand Total : </div>';
                }
                $isSetSubTotalText = true;
            }


            foreach ($sum as $sum_col) {
                if ($sum_col == $header[$i][0]["text"]) {
                    if (isset($this->group_grand_total[$sum_col])) {
                        $sum_text = $this->group_grand_total[$sum_col];
                    }
                }
            }

            if (is_numeric($sum_text)) {
                $sum_text = number_format($sum_text, 2);
            }


            $table .= '<th style="width:' . $header[$i][0]["width"] . 'px;display:' . $display . ';font-size:' . $font_size . 'px;text-align:' . $header[$i][0]["align"] . ';">';
            $table .= '<div style="width:' . $header[$i][0]["width"] . 'px;display:' . $display . ';font-size:' . $font_size . 'px;text-align:' . $header[$i][0]["align"] . ';white-space: nowrap;overflow: hidden;text-overflow: clip;">';
            $table .= $sum_text;
            $table .= '</div>';
            $table .= '</th>';
        }
        $table .= '</tr>';
        $table .= '</table>';
        return $table;
    }





    private function createNumber($obj)
    {

        $value = "";
        if (isset($obj['value'])) {
            $value = $obj['value'];
            if (count(explode('@_$', $value)) > 1) {
                $value = $this->parameters[explode('@_$', $value)[1]];
            }
        }

        $align = "left";
        if (isset($obj['align'])) {
            $align = $obj['align'];
        }

        $border_top = "0";
        if (isset($obj['border-top'])) {
            $border_top = $obj['border-top'];
        }

        $border_bottom = "0";
        if (isset($obj['border-bottom'])) {
            $border_bottom = $obj['border-bottom'];
        }

        $border_left = "0";
        if (isset($obj['border-left'])) {
            $border_left = $obj['border-left'];
        }

        $border_right = "0";
        if (isset($obj['border-right'])) {
            $border_right = $obj['border-right'];
        }

        $bgcolor = "#ffffff";
        if (isset($obj['bg-color'])) {
            $bgcolor = $obj['bg-color'];
        }


        $color = "#000000";
        if (isset($obj['color'])) {
            $color = $obj['color'];
        }

        $width = "100%";
        if (isset($obj['width'])) {
            $width = $obj['width'];
        }

        $height = "30";
        if (isset($obj['height'])) {
            $height = $obj['height'];
        }

        $position = "";
        if (isset($obj['position'])) {
            $position = $obj['position'];
        }

        $x = "0";
        if (isset($obj['x'])) {
            $x = $obj['x'];
        }

        $y = "0";
        if (isset($obj['y'])) {
            $y = $obj['y'];
        }

        $opacity = "1";
        if (isset($obj['opacity'])) {
            $opacity = $obj['opacity'];
        }

        $border_color = "#000000";
        if (isset($obj['border-color'])) {
            $border_color = $obj['border-color'];
        }

        $padding_top = "0";
        if (isset($obj['padding-top'])) {
            $padding_top = $obj['padding-top'];
        }

        $font_size = "13";
        $font_style = "";
        $font_name = "";
        if (isset($obj['font'])) {

            $font = $obj['font'];
            if (isset($font["size"])) {
                $font_size = $font["size"];
            }
            if (isset($font["style"])) {
                $font_style = $font["style"];
            }
            if (isset($font["name"])) {
                $font_name = $font["name"];
            }
        }

        if (isset($obj['format'])) {

            $format = $obj['format'];

            if ($value == "") {
                $value = "0";
            }
            $format = explode(".", $format);

            if (count($format) > 1) {
                $value = number_format($value, strlen($format[1]));
            }
        }




        $style = "text-align:" . $align . ";";
        $style .= "border-top:" . $border_top . "px solid " . $border_color . ";";
        $style .= "border-bottom:" . $border_bottom . "px solid " . $border_color . ";";
        $style .= "border-right:" . $border_right . "px solid " . $border_color . ";";
        $style .= "border-left:" . $border_left . "px solid " . $border_color . ";";
        $style .= "background-color:" . $bgcolor . ";";
        $style .= "color:" . $color . ";";
        $style .= "width:" . $width . "px;";
        $style .= "height:" . $height . "px;";
        $style .= "position: " . $position . ";";
        $style .= "top:" . $y . "px;";
        $style .= "left:" . $x . "px;";
        $style .= "opacity:" . $opacity . "";
        $style .= "padding-top:" . $padding_top . "px;";
        //$style .= "font-family:" . $font_name . ";";
        $style .= "font-size:" . $font_size . "px;";
        $style .= "font-weight:" . $font_style . ";";


        $label = '<div style="' . $style . '">';
        $label .= $value;
        $label .= '</div>';
        return $label;
    }



    private function createNumberArgs($obj, $value)
    {

        $align = "left";
        if (isset($obj['align'])) {
            $align = $obj['align'];
        }

        $border_top = "0";
        if (isset($obj['border-top'])) {
            $border_top = $obj['border-top'];
        }

        $border_bottom = "0";
        if (isset($obj['border-bottom'])) {
            $border_bottom = $obj['border-bottom'];
        }

        $border_left = "0";
        if (isset($obj['border-left'])) {
            $border_left = $obj['border-left'];
        }

        $border_right = "0";
        if (isset($obj['border-right'])) {
            $border_right = $obj['border-right'];
        }

        $bgcolor = "#ffffff";
        if (isset($obj['bg-color'])) {
            $bgcolor = $obj['bg-color'];
        }


        $color = "#000000";
        if (isset($obj['color'])) {
            $color = $obj['color'];
        }

        $width = "100%";
        if (isset($obj['width'])) {
            $width = $obj['width'];
        }

        $height = "30";
        if (isset($obj['height'])) {
            $height = $obj['height'];
        }

        $position = "";
        if (isset($obj['position'])) {
            $position = $obj['position'];
        }

        $x = "0";
        if (isset($obj['x'])) {
            $x = $obj['x'];
        }

        $y = "0";
        if (isset($obj['y'])) {
            $y = $obj['y'];
        }

        $opacity = "1";
        if (isset($obj['opacity'])) {
            $opacity = $obj['opacity'];
        }

        $border_color = "#000000";
        if (isset($obj['border-color'])) {
            $border_color = $obj['border-color'];
        }

        $padding_top = "0";
        if (isset($obj['padding-top'])) {
            $padding_top = $obj['padding-top'];
        }

        $font_size = "13";
        $font_style = "";
        $font_name = "";
        if (isset($obj['font'])) {

            $font = $obj['font'];
            if (isset($font["size"])) {
                $font_size = $font["size"];
            }
            if (isset($font["style"])) {
                $font_style = $font["style"];
            }
            if (isset($font["name"])) {
                $font_name = $font["name"];
            }
        }

        if (isset($obj['format'])) {

            $format = $obj['format'];

            if ($value == "") {
                $value = "0";
            }
            $format = explode(".", $format);

            if (count($format) > 1) {
                $value = number_format($value, strlen($format[1]));
            }
        }




        $style = "text-align:" . $align . ";";
        $style .= "border-top:" . $border_top . "px solid " . $border_color . ";";
        $style .= "border-bottom:" . $border_bottom . "px solid " . $border_color . ";";
        $style .= "border-right:" . $border_right . "px solid " . $border_color . ";";
        $style .= "border-left:" . $border_left . "px solid " . $border_color . ";";
        $style .= "background-color:" . $bgcolor . ";";
        $style .= "color:" . $color . ";";
        $style .= "width:" . $width . "px;";
        $style .= "height:" . $height . "px;";
        $style .= "position: " . $position . ";";
        $style .= "top:" . $y . "px;";
        $style .= "left:" . $x . "px;";
        $style .= "opacity:" . $opacity . "";
        $style .= "padding-top:" . $padding_top . "px;";
        //$style .= "font-family:" . $font_name . ";";
        $style .= "font-size:" . $font_size . "px;";
        $style .= "font-weight:" . $font_style . ";";


        $label = '<div style="' . $style . '">';
        $label .= $value;
        $label .= '</div>';
        return $label;
    }
}
