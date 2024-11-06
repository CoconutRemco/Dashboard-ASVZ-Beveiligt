# Beveiligingsbeleid

- **Authenticatie**: Sanctum geïmplementeerd voor API's met rolgebaseerde toegangscontrole.
- **Encryptie**: Alle wachtwoorden worden geëncrypteerd met bcrypt.
- **Rate Limiting**: API-routes zijn gelimiteerd tot 60 verzoeken per minuut.
- **CSRF-bescherming**: CSRF-token wordt toegevoegd aan alle POST-verzoeken.
