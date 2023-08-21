# Documentation de l'API Split-Vacation

Cette documentation vous guide à travers l'utilisation de l'API Split-Vacation. Cette API offre une solution fluide pour la répartition des périodes de congés sur plusieurs mois.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les composants suivants :
- PHP
- Composer

## Configuration de l'Environnement

1. Clonez votre projet.
2. Accédez au dossier de l'application en utilisant la commande `cd` dans votre terminal.
3. Exécutez la commande `composer install` dans votre terminal.
4. Copiez le fichier `.env.example` en le renommant en `.env`.
5. Exécutez la commande `php artisan key:generate` dans votre terminal.


## Exécution de l'API

1. Ouvrez votre terminal et accédez au répertoire du projet.
2. Exécutez la commande : `php artisan serve`.
3. Accédez à l'URL fournie (par exemple, [http://127.0.0.1:8000]) dans votre navigateur préféré.

## Effectuer des Requêtes API

Pour répartir les périodes de congés, suivez ces étapes :

1. Utilisez un outil tel que POSTMAN pour effectuer des requêtes API.
2. Créez une requête POST avec le corps JSON suivant :
   ```json
   {
       "token": "PrimoBoxToken",
       "start_date": "2023-08-01",
       "end_date": "2023-10-16"
   }
   ```
3- Définissez l'URL de la requête sur : http://127.0.0.1:8000/api/split-vacation.
4- Envoyez la requête.

## Réponses de l'API

L'API fournit les réponses suivantes :

- Si les dates de début et de fin tombent dans le même mois :
```json
{
    "code": 200,
    "message": "Request was successful",
    "data": [
        2,
        "Août 2023"
    ]
}
```

- Si la période de congé s'étend sur plusieurs mois :
```json
{
    "code": 200,
    "message": "Request was successful",
    "data": [
        [
            19,
            "Août 2023"
        ],
        [
            6,
            "Septembre 2023"
        ]
    ]
}
```
- Si les congés débutent avant la date actuelle, si la date de fin précède la date de début ou si les dates fournies ne sont pas valides:
```json
{
    "code": 400,
    "message": "Request was malformed",
    "data": null
}
```

- Si Token n'est pas valide :
```json
{
    "code": 404,
    "message": "Token not valid",
    "data": null
}
```

- Si les congés débutent ou se terminent pendant le week-end :
```json
{
    "code": 405,
    "message": "Vacations should not start or end on weekends",
    "data": null
}
```
- Pour toute autre erreur :
```json
{
    "code": 500,
    "message": "Internal Server error",
    "data": null
}
```