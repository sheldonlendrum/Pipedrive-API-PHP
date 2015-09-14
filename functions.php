<?php

    function build_table($data) {
        $html = '<table width="100%" border="1">' ."\n";
        foreach($data as $key => $value) {
        $html .= '<tr>' ."\n";
            $html .= "\t". '<td width="300" style="width:300px">' . $key . '</td>' ."\n";
            if(is_object($value) or is_array($value)) {
                 $html .= '<td>'. build_table($value) .'</td>';
            } else {
                $html .= "\t". '<td>' . $value . '</td>' ."\n";
            }
        $html .= '</tr>' ."\n";
        }
        $html .= '</table>' ."\n\n\n";
        return $html;
    }

