<?php

declare(strict_types=1);

namespace Cvgore\RandomThings\Serializer;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class QueryStringEncoder implements EncoderInterface, DecoderInterface
{
	public function encode($data, string $format, array $context = []): string
	{
		return http_build_query($data, encoding_type: PHP_QUERY_RFC3986);
	}

	public function supportsEncoding(string $format, array $context = []): bool
	{
		return $format === 'querystring';
	}

	public function decode(string $data, string $format, array $context = []): array
	{
		parse_str($data, $output);

		return $output;
	}

	public function supportsDecoding(string $format, array $context = []): bool
	{
		return $format === 'querystring';
	}
}
