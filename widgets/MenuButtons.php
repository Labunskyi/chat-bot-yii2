<?php
namespace app\widgets;


class MenuButtons
{
    public static function gen($items, $lines){
        $line_o = $lines;
        if($lines > count($items)){
            $lines = count($items);
        }
        $col = 12 / $lines;
        $k = 0;
        $html = '';
        foreach ($items as $key => $item ){
            if($lines == $key && !($line_o % 2 == 0)){
                $col = 12;
            }
            $html .= '<div class="col-'.$col.'" style="padding: 2px;"><button type="button" class="btn btn-info col-12">'.$item->name.'</button></div>';

        }
        return $html;
    }

}