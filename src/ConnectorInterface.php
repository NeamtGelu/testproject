<?php

namespace Gelu;


interface ConnectorInterface
{
    public function makeRequest($path);
}
