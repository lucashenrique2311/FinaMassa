# 🔐 Credenciais de Login Inicial

## Usuário Administrador Criado

Foi criado um usuário administrador inicial no sistema:

### Credenciais
- **Email**: `admin@sistema.com`
- **Senha**: `admin123`

### Como Fazer Login

1. Acesse a URL: `http://seu-dominio/` ou `http://seu-dominio/Login`
2. Digite o email: `admin@sistema.com`
3. Digite a senha: `admin123`
4. Clique em "Entrar"

### Importante ⚠️

**ALTERE A SENHA APÓS O PRIMEIRO ACESSO!**

Esta é uma senha padrão e deve ser alterada imediatamente após o primeiro login por questões de segurança.

## Como Criar Mais Usuários

Após fazer login, você pode:
1. Acessar o CRUD de Usuários (quando implementado)
2. Criar novos usuários através da interface
3. Ou usar o seeder novamente para criar usuários de teste

## Executar Seeder Novamente

Se precisar recriar o usuário admin:

```bash
php spark db:seed UsuarioInicialSeeder
```

O seeder verifica se o usuário já existe antes de criar, então é seguro executar múltiplas vezes.

## Estrutura do Usuário

O usuário criado tem:
- **Nome**: Administrador do Sistema
- **Email**: admin@sistema.com
- **Senha**: admin123 (hash SHA1)
- **Admin**: Sim (1)
- **Ativo**: Sim (1)
- **ID Cliente**: null (admin do sistema)

## Próximos Passos

1. ✅ Login dinâmico - CONCLUÍDO
2. ✅ Usuário inicial criado - CONCLUÍDO
3. ⏭️ CRUD de Usuários - PRÓXIMO

