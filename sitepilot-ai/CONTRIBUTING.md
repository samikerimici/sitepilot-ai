# Contribuer à SitePilot AI

Ce dépôt sert d’abord à l’apprentissage de GitHub et au suivi interne du projet.

## Avant de commencer

1. Ouvrez une issue pour décrire le bug ou l’amélioration.
2. Créez une branche depuis `main`.
3. Effectuez des changements ciblés.
4. Vérifiez la syntaxe PHP.
5. Ouvrez une pull request claire.

## Nommage des branches

```text
feature/nom-court
fix/nom-court
docs/nom-court
release/1.2.0
```

## Messages de commit

Exemples :

```text
feat: ajoute l’aperçu du contenu
fix: corrige la vérification du nonce
docs: précise l’installation WordPress
chore: prépare la version 1.1.0
```

## Vérification PHP

```bash
find . -name "*.php" -print0 | xargs -0 -n1 php -l
```

## Pull request

La pull request doit préciser :

- le besoin traité ;
- les changements réalisés ;
- la méthode de test ;
- les éventuels risques ou limitations.

## Données sensibles

Aucune clé API ni donnée client ne doit être ajoutée au dépôt.
