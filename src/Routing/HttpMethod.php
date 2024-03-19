<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Routing;

enum HttpMethod: string
{
	case Get = 'GET';
	case Post = 'POST';
}
