# Application Full-Stack

### Étapes d'installation

**1 Clonez le dépôt de l'application :**
```
git clone https://github.com/younesprogramme/Full-Stack.git
```
**Accédez au dossier de l'application :**
```
cd Full-Stack
```
**Installez les dépendances via Composer :**
```
composer install
```
**Modifiez le fichier .env pour configurer la base de données et l'envoi d'emails :**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_tickets
DB_USERNAME=root
DB_PASSWORD=secret

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@example.com
MAIL_PASSWORD=votre-mot-de-passe
MAIL_ENCRYPTION=tls

```
**Générez une clé d'application :**
```
php artisan key:generate
```
