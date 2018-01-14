<?php
/**
 * Created by PhpStorm.
 * User: Hugo LIEGEARD
 * Date: 10/10/2017
 * Time: 14:50
 */

namespace biyn\lvpBundle\Entity;


class Encode
{
    private $data = array (0 => 'a5T9b7H5y5Ui7Oy4bJ9y7Gh13eoU8z8njhzoiibhzouvz', 1 => 'Kj9I7Iy8u52T3r96ovzave4b13Jh88E3bY8a7b1C2p3C', 2 => 'P1l85i8J6ezzehzehyGR8f7p1z2B9bezbydJ3U1y5t8GB8d', 3 => 'OtY9U7n4G2R5u1a9w3S5c2hez98h4eq98rhyB1u23TT8', 4 => 'N5v3BgC8v7d1D2b8Ju3yzhezhz95t5G791E5Hbze89b46Jx3s48z', 5 => 'P9U7o4H1g6R3zebzebezebezhzehy9z97bezt5D2b1j9z98uHtFZ4K9p7io2', 6 => 'P5Uo9H7g2R2t3D8bEVvei7us8dvtE579j5z1u4H7t6F3ZK3p8i5o', 7 => 'B1u3Y5Tve26j7G9g4fA8Y96v5Iu1ive87v4e75UI54rzq9z9b4Uc2y7t', 8 => 'n5zt7Y4b1G9e3D9A8i7j52vnc8z552i1eveev87evezv6ev4e8evi3ucZ78C', 9 => 'J12k5l79H8bev94ez5vez57ru87r5b52n9Zs7uY9VzH5uU4T6CZ8J24c' );

    //Penser a faire une generation aleatoire du tableau via par exemple une generation lors de la creation de session de connexion ...
    public function encode($value) {

        //Decompose les chiffres dans un tableau
        $array = str_split ( $value );

        //Initialisation
        $final = null;
        $data = $this->data;

        foreach ( $array as $col ) {
            $final .= $data [$col] . 'm';
        }

        return $final;

    }
    //décode l'ID chiffré
    public function decode($value) {
        $array = explode ( 'm', $value );

        //Initialisation
        $final = null;
        $data = $this->data;

        foreach ( $array as $col ) {
            $final .= array_search ( $col, $data );
        }

        return $final;

    }
}