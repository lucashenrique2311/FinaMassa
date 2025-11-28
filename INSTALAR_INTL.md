# Instalação da Extensão PHP intl

## Problema
O CodeIgniter 4 requer a extensão `intl` do PHP, que não está instalada no servidor.

## Solução

### Para PHP 7.4:
```bash
sudo apt update
sudo apt install php7.4-intl
```

### Para PHP 8.1+ (recomendado):
```bash
sudo apt update
sudo apt install php8.1-intl
```

### Verificar se foi instalado:
```bash
php -m | grep intl
```

Deve retornar: `intl`

### Reiniciar serviços (se necessário):
```bash
# Para Apache
sudo systemctl restart apache2

# Para Nginx + PHP-FPM
sudo systemctl restart php7.4-fpm
# ou
sudo systemctl restart php8.1-fpm
```

## Após instalação

Execute novamente:
```bash
php spark migrate
```

## Nota
A extensão `intl` é necessária para:
- Internacionalização (i18n)
- Formatação de datas e números
- Localização
- Classes como `Locale`, `NumberFormatter`, etc.

