<?php 

interface IRouteHandler 
{
    public function OnGET(array $args): void;
    public function OnPOST(array $args): void;
    public function OnPUT(array $args): void;
    public function OnDELETE(array $args): void;
}