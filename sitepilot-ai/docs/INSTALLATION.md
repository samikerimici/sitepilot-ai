# Installation

## Méthode recommandée pour les tests

1. Effectuez une sauvegarde du site.
2. Utilisez un site de préproduction ou un WordPress local.
3. Téléchargez le ZIP depuis la section **Releases** du dépôt GitHub.
4. Dans WordPress, ouvrez **Extensions > Ajouter une extension**.
5. Cliquez sur **Téléverser une extension**.
6. Sélectionnez le ZIP puis cliquez sur **Installer maintenant**.
7. Activez SitePilot AI.

## Installation manuelle

Copiez le dossier du plugin dans :

```text
wp-content/plugins/sitepilot-ai/
```

Activez ensuite l’extension depuis l’administration WordPress.

## Après activation

L’activation :

- initialise les réglages ;
- crée l’état de l’offre découverte ;
- crée les historiques nécessaires ;
- planifie une analyse quotidienne via WP-Cron.

## Désinstallation

La désactivation arrête la tâche quotidienne. La désinstallation peut supprimer les options du plugin selon la logique définie dans `uninstall.php`. Sauvegardez les données nécessaires avant une suppression définitive.
