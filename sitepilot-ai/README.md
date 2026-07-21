# SitePilot AI

> Le copilote IA de Lorraine Digital pour analyser, conseiller et accompagner la gestion d’un site WordPress.

![Version](https://img.shields.io/badge/version-1.1.0--rc1-blue)
![WordPress](https://img.shields.io/badge/WordPress-6.5%2B-21759b)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb4)
![License](https://img.shields.io/badge/license-GPL--2.0--or--later-green)
![Status](https://img.shields.io/badge/status-release%20candidate-orange)

SitePilot AI centralise plusieurs outils utiles aux administrateurs WordPress : analyse du site, recommandations, génération de contenu, création de méta-descriptions, optimisation des textes ALT et suivi des scores.

Cette version `1.1.0-rc1` est destinée à la prise en main de GitHub et aux tests avant déploiement client.

## Fonctionnalités

- Tableau de bord avec score global et priorités.
- Analyse SEO, contenu, images, sécurité et mises à jour.
- Historique des 30 dernières analyses.
- Studio de contenu pour les articles, actualités, pages et FAQ.
- Génération locale ou via l’API OpenAI.
- Création automatique de brouillons WordPress.
- Génération de méta-descriptions compatibles avec Yoast SEO.
- Complétion des textes ALT manquants pour les images.
- Assistant contextualisé avec les informations de l’entreprise.
- Analyse quotidienne planifiée avec WP-Cron.

## Prérequis

- WordPress 6.5 ou version ultérieure.
- PHP 7.4 ou version ultérieure.
- Droits d’administration WordPress pour les réglages.
- Une clé API OpenAI uniquement pour la génération distante. Le mode local reste disponible sans clé.

## Installation

### Depuis WordPress

1. Téléchargez l’archive ZIP de la release.
2. Dans WordPress, ouvrez **Extensions > Ajouter une extension > Téléverser une extension**.
3. Sélectionnez le ZIP, installez puis activez SitePilot AI.
4. Ouvrez **SitePilot AI > Réglages**.
5. Complétez le profil de l’entreprise et choisissez le fournisseur de génération.

### Depuis le dépôt

```bash
git clone https://github.com/VOTRE-COMPTE/sitepilot-ai.git
```

Copiez ensuite le dossier `sitepilot-ai` dans `wp-content/plugins/`, puis activez l’extension depuis l’administration WordPress.

## Configuration OpenAI

Dans **SitePilot AI > Réglages** :

1. Sélectionnez `OpenAI` comme fournisseur.
2. Saisissez votre clé API.
3. Vérifiez le modèle configuré.
4. Enregistrez les réglages.

La clé est enregistrée dans les options WordPress de cette version de test. Évitez d’utiliser une clé de production sur un environnement partagé ou non sécurisé.

## Documentation

- [Installation](docs/INSTALLATION.md)
- [Configuration](docs/CONFIGURATION.md)
- [Studio de contenu](docs/CONTENT-STUDIO.md)
- [Analyse et rapports](docs/ANALYSE-ET-RAPPORTS.md)
- [Architecture développeur](docs/DEVELOPER.md)
- [Publication d’une nouvelle version](docs/RELEASES.md)
- [FAQ](docs/FAQ.md)

## Structure du projet

```text
sitepilot-ai/
├── .github/                  Modèles et automatisations GitHub
├── app/
│   ├── Core/                 Initialisation et interface d’administration
│   └── Services/             Analyse, assistant et génération de contenu
├── assets/
│   ├── css/                  Styles de l’administration
│   ├── js/                   Scripts de l’administration
│   └── screenshots/          Futures captures du plugin
├── docs/                     Documentation du projet
├── sitepilot-ai.php          Fichier principal de l’extension
├── uninstall.php             Nettoyage lors de la désinstallation
└── readme.txt                Présentation au format WordPress
```

## Développement local

Vérifiez la syntaxe PHP avant chaque publication :

```bash
find . -name "*.php" -print0 | xargs -0 -n1 php -l
```

Le workflow GitHub Actions inclus exécute automatiquement cette vérification à chaque push et pull request.

## État du projet

Cette release candidate doit être testée sur un environnement de préproduction avant toute installation sur un site client. Effectuez une sauvegarde avant les actions automatiques en masse.

## Roadmap

Consultez [ROADMAP.md](ROADMAP.md) pour les pistes envisagées. La roadmap n’est pas un engagement contractuel.

## Sécurité

Ne publiez pas de vulnérabilité exploitable dans une issue publique. Consultez [SECURITY.md](SECURITY.md).

## Contribution

Le projet peut être utilisé comme dépôt d’apprentissage interne. Les règles proposées sont détaillées dans [CONTRIBUTING.md](CONTRIBUTING.md).

## Licence

SitePilot AI est distribué sous licence **GPL-2.0-or-later**. Consultez [LICENSE](LICENSE).

## Éditeur

**Lorraine Digital**  
<https://lorraine.digital>  
Contact : contact@lorraine.digital
