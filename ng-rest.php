<?php

namespace sacaliens;

class Rest
{
    protected $dataProvider;

    public function __construct($dataProvider) {
        $this->dataProvider = $dataProvider;
        header("Content-Type: application.json");
    }

    public function index()
    {
        $links = $this->dataProvider->getAllLinks();

        echo json_encode($links);
    }
}
