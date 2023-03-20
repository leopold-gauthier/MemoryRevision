<?php
session_start();

class Card
{
    public $id_card;
    public $img_face_down;
    public $img_face_up;
    public $state;

    function get_id_card()
    {
        return $this->id_card;
    }

    function get_img_face_down()
    {
        return $this->img_face_down;
    }

    function get_img_face_up()
    {
        return $this->img_face_up;
    }

    function get_state()
    {
        return $this->state;
    }

    function set_id_card($id_card)
    {
        $this->id_card = $id_card;
    }

    function set_img_face_down($img_face_down)
    {
        $this->img_face_down = $img_face_down;
    }

    function set_img_face_up($img_face_up)
    {
        $this->img_face_up = $img_face_up;
    }

    function set_state($state)
    {
        $this->state = $state;
    }

    function __construct($id_card, $img_face_down, $img_face_up, $state)
    {
        $this->id_card = $id_card;
        $this->img_face_down = $img_face_down;
        $this->img_face_up = $img_face_up;
        $this->state = $state;
    }

}

//$user = new Card();
