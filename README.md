# Documentation de l'API Split Congés

Cette documentation vous guide à travers l'utilisation de l'API Split Congés. Cette API offre une solution fluide pour la répartition des périodes de congés sur plusieurs mois.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les composants suivants :
- PHP
- Composer

## Configuration de l'Environnement

1. Installez PHP et Composer sur votre système.

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
       "start_date": "07-08-2023",
       "end_date": "08-09-2023"
   }
   ```
3- Définissez l'URL de la requête sur : http://127.0.0.1:8000/api/split-conges.
4- Envoyez la requête.

## Réponses de l'API

L'API fournit les réponses suivantes :

- Si les dates de début et de fin tombent dans le même mois :
```json
{
    "code": 200,
    "message": "La requête a réussi",
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
    "message": "La requête a réussi",
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
- Si les dates fournies ne sont pas valides :
```json
{
    "code": 400,
    "message": "La requête était malformée",
    "data": null
}
```
- Pour toute autre erreur :
```json
{
    "code": 500,
    "message": "Erreur interne du serveur",
    "data": null
}
```