#!/usr/bin/env bash
# Vila Baleira Theme — Auto Deployment Script

set -e

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." >/dev/null 2>&1 && pwd )"
cd "$DIR"

echo "📦 1/2 Compiling Tailwind CSS..."
npm run build

echo "🚀 2/2 Syncing theme to remote server (147.182.195.140)..."
expect -c '
set timeout 300
spawn rsync -avz --delete \
  --exclude "node_modules" \
  --exclude ".git" \
  --exclude ".DS_Store" \
  --exclude ".agents" \
  -e "ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -p 22" \
  ./ \
  ziyakatevo1858@147.182.195.140:web/vbl.instawp.site/public_html/wp-content/themes/vila-baleira-theme/

expect {
    "password:" {
        send "cdFJrE1nW6Uxa74CsyPm\r"
        exp_continue
    }
    eof
}
'

echo "✅ Deploy completed successfully!"
