<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Util
 *
 * @author Pedro
 */
class Util {
    /**
     * devuelve entre 0 y 1
     */
    public static function random() {
        return rand() / getrandmax();
    }
}
