# Publier une nouvelle version

## 1. Préparer le code

- Mettre à jour la version dans `sitepilot-ai.php`.
- Mettre à jour `readme.txt`.
- Compléter `CHANGELOG.md`.
- Tester l’extension sur un site de préproduction.
- Vérifier que GitHub Actions est au vert.

## 2. Créer un tag

Exemple pour une release candidate :

```bash
git tag -a v1.1.0-rc1 -m "SitePilot AI 1.1.0 RC1"
git push origin v1.1.0-rc1
```

Exemple pour une version stable :

```bash
git tag -a v1.1.0 -m "SitePilot AI 1.1.0"
git push origin v1.1.0
```

## 3. Créer la release GitHub

1. Ouvrir l’onglet **Releases** du dépôt.
2. Cliquer sur **Draft a new release**.
3. Choisir le tag.
4. Ajouter un titre et les notes de version.
5. Cocher **pre-release** pour une version RC.
6. Joindre le ZIP installable du plugin.
7. Publier la release.

## 4. Convention de versions

```text
MAJEURE.MINEURE.CORRECTIF
```

Exemples :

- `1.1.0-rc1` : première release candidate de la 1.1.0 ;
- `1.1.0` : version stable ;
- `1.1.1` : correction sans nouvelle fonctionnalité majeure ;
- `1.2.0` : ajout de fonctionnalités compatibles avec la série 1.x.
