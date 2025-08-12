<?php

namespace database;

enum Status: int
{
    case HTTP_OK = 200;
    case HTTP_BAD_REQUEST = 400;
    case HTTP_FORBIDDEN = 403;
    case HTTP_NOT_FOUND = 404;
}
