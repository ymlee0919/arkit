<?php

/**
 * Interface for a value object representation of an HTTP cookie.
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie
 */
interface CookieInterface
{
    /**
     * Cookies will be sent in all contexts, i.e in responses to both
     * first-party and cross-origin requests. If `SameSite=None` is set,
     * the cookie `Secure` attribute must also be set (or the cookie will be blocked).
     */
    public const SAMESITE_NONE = 'none';

    /**
     * Cookies are not sent on normal cross-site subrequests (for example to
     * load images or frames into a third party site), but are sent when a
     * user is navigating to the origin site (i.e. when following a link).
     */
    public const SAMESITE_LAX = 'lax';

    /**
     * Cookies will only be sent in a first-party context and not be sent
     * along with requests initiated by third party websites.
     */
    public const SAMESITE_STRICT = 'strict';

    /**
     * RFC 6265 allowed values for the "SameSite" attribute.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite
     */
    public const ALLOWED_SAMESITE_VALUES = [
        self::SAMESITE_NONE,
        self::SAMESITE_LAX,
        self::SAMESITE_STRICT,
    ];

    /**
     * Expires date format.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Date
     * @see https://tools.ietf.org/html/rfc7231#section-7.1.1.2
     */
    public const EXPIRES_FORMAT = 'D, d-M-Y H:i:s T';

    /**
     * Returns a unique identifier for the cookie consisting
     * of its prefixed name, path, and domain.
     */
    public function getId(): string;

    /**
     * Gets the cookie name.
     */
    public function getName(): string;

    /**
     * Gets the cookie value.
     */
    public function getValue(): string;

    /**
     * Gets the cookie name.
     * @param string $name The new name of the cookie
     * @return self
     */
    public function setName(string $name): self;

    /**
     * Gets the cookie value.
     * @param string $value The new value of the cookie
     * @return self
     */
    public function setValue(string $value): self;

    /**
     * Gets the time in Unix timestamp the cookie expires.
     */
    public function getExpiresTimestamp(): int;

    /**
     * Gets the formatted expires time.
     */
    public function getExpiresString(): string;

    /**
     * Set the cookie expired
     * @return $this
     */
    public function setExpired() : self;

    /**
     * Checks if the cookie is expired.
     */
    public function isExpired(): bool;

    /**
     * Gets the "Max-Age" cookie attribute.
     */
    public function getMaxAge(): int;

    /**
     * Gets the "Path" cookie attribute.
     */
    public function getPath(): string;

    /**
     * Gets the "Path" cookie attribute.
     * @param string $path The new path of the cookie
     * @return self
     */
    public function setPath(string $path): self;

    /**
     * Gets the "Domain" cookie attribute.
     */
    public function getDomain(): string;

    /**
     * Gets the "Secure" cookie attribute.
     *
     * Checks if the cookie is only sent to the server when a request is made
     * with the `https:` scheme (except on `localhost`), and therefore is more
     * resistent to man-in-the-middle attacks.
     */
    public function isSecure(): bool;

    /**
     * Gets the "HttpOnly" cookie attribute.
     *
     * Checks if JavaScript is forbidden from accessing the cookie.
     */
    public function isHTTPOnly(): bool;

    /**
     * Gets the "SameSite" cookie attribute.
     */
    public function getSameSite(): string;

    /**
     * Gets the options that are passable to the `setcookie` variant
     * available on PHP 7.3+
     *
     * @return array<string, mixed>
     */
    public function getOptions(): array;

    /**
     * Returns the Cookie as a header value.
     */
    public function toHeaderString(): string;

    /**
     * Returns the string representation of the Cookie object.
     *
     * @return string
     */
    public function __toString();

    /**
     * Returns the array representation of the Cookie object.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;


    /**
     * Dispatch the cookie
     * @return void
     */
    public function dispatch() : void;
}
