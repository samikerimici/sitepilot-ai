# Configuration

Ouvrez **SitePilot AI > Réglages** dans WordPress.

## Profil de l’entreprise

Renseignez au minimum :

- nom de l’entreprise ;
- activité ;
- services ;
- ville et zone couverte ;
- ton rédactionnel ;
- mots-clés prioritaires.

Ces informations contextualisent l’assistant et le studio de contenu.

## Fournisseur de génération

### Mode local

Aucune clé API n’est requise. Le plugin produit une structure éditoriale prête à être complétée.

### OpenAI

Le plugin envoie une demande à l’API OpenAI et attend un JSON contenant :

- `title` ;
- `content` ;
- `excerpt` ;
- `meta_description`.

Saisissez la clé API et le modèle dans les réglages.

## Précautions

- Ne publiez jamais votre clé API sur GitHub.
- Utilisez une clé limitée et surveillez son usage.
- Relisez toujours les contenus générés avant publication.
- Ne considérez pas les recommandations comme un audit de sécurité exhaustif.
