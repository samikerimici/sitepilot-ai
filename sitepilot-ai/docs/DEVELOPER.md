# Documentation développeur

## Point d’entrée

`sitepilot-ai.php` déclare les constantes du plugin, charge la classe principale et enregistre les hooks d’activation et de désactivation.

## Classe principale

`app/Core/Plugin.php` :

- initialise les services ;
- déclare les pages d’administration ;
- charge les assets ;
- enregistre les réglages ;
- traite les actions AJAX ;
- planifie l’analyse quotidienne.

## Services

- `Scanner.php` : collecte les indicateurs et construit les recommandations.
- `Assistant.php` : répond aux demandes depuis l’interface d’assistance.
- `ContentGenerator.php` : génère les contenus localement ou via OpenAI.

## Options WordPress principales

```text
spai_settings
spai_first_activation
spai_plan
spai_activity
spai_scan_history
spai_last_scan
```

## Action planifiée

```text
spai_daily_scan
```

## Actions AJAX

```text
spai_scan
spai_assistant
spai_create_content
spai_bulk_alt
spai_generate_meta
```

Les actions utilisent un nonce `spai` et vérifient les capacités WordPress appropriées.

## Vérification locale

```bash
find . -name "*.php" -print0 | xargs -0 -n1 php -l
```

## Recommandations avant production

- Ajouter des tests unitaires et d’intégration.
- Renforcer la gestion et le stockage des secrets.
- Vérifier les performances des requêtes sur de grandes bases.
- Tester le multisite si celui-ci doit être pris en charge.
- Ajouter un processus de migration des options entre versions.
