<?php
namespace Librette\LinkBuilder;

interface Exception
{

}


class InvalidStateException extends \RuntimeException implements Exception
{

}


class InvalidLinkException extends \Exception implements Exception
{

}
