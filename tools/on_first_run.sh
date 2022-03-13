
#Will need to generate a fresh APP_KEY (used for session data / some encryption functions)
???

#Will need to generate random encryption keys and RSA files
php artisan encrypt:generate
php artisan vendor:publish --provider="RichardStyles\EloquentEncryption\EloquentEncryptionServiceProvider" --tag="config"
