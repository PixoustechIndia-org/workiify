<?php
/**
 * Raised by Service_model when a service can't be saved (database offline, service missing).
 * Callers catch it like any other runtime error.
 */
class ServiceException extends RuntimeException {
}
