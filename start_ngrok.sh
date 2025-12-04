#!/bin/bash

# ==========================================
# Laravel ngrok launcher for custom domain (HTTPS)
# Compatible with systems without 'pgrep'
# ==========================================

CUSTOM_DOMAIN="toko_ppob.test:80"  # Use port 80 for HTTP to avoid SSL issues
ENV_FILE=".env"
ENV_KEY="APP_URL"

# Check and stop existing ngrok processes
if ps aux | grep '[n]grok' > /dev/null; then
  echo "🛑 Stopping existing ngrok process..."
  ps aux | grep '[n]grok' | awk '{print $2}' | xargs kill -9
  sleep 2
fi

# Start ngrok in background (rewrite host header)
echo "🚀 Starting ngrok for $CUSTOM_DOMAIN ..."
nohup ngrok http "$CUSTOM_DOMAIN" --host-header=rewrite --scheme=https > /dev/null 2>&1 &

# Wait for ngrok to initialize
sleep 5

# Fetch the public URL from ngrok API (force HTTPS)
URL=$(curl -s http://127.0.0.1:4040/api/tunnels \
  | grep -oE "https://[0-9a-zA-Z.-]+\.ngrok-free\.app" \
  | head -n1)

# Ensure URL uses HTTPS
if [[ ! $URL == https://* ]]; then
    URL="https://${URL#*//}"  # Add https:// if missing
fi

if [ -z "$URL" ]; then
    echo "❌ Failed to retrieve ngrok URL. Check if ngrok started correctly."
    exit 1
fi

echo "✅ Ngrok Public URL: $URL"

# Update .env APP_URL
if grep -q "^$ENV_KEY=" "$ENV_FILE"; then
    # macOS uses different sed syntax
    if [[ "$OSTYPE" == "darwin"* ]]; then
        sed -i '' "s|^$ENV_KEY=.*|$ENV_KEY=$URL|" "$ENV_FILE"
    else
        sed -i "s|^$ENV_KEY=.*|$ENV_KEY=$URL|" "$ENV_FILE"
    fi
else
    echo "$ENV_KEY=$URL" >> "$ENV_FILE"
fi

echo "🔄 Updated $ENV_KEY in $ENV_FILE"

# Clear and cache Laravel config
if [ -f artisan ]; then
    echo "🧹 Clearing and caching Laravel config..."
    php artisan config:clear
    php artisan config:cache
fi

grep "^$ENV_KEY=" "$ENV_FILE"
echo "✅ Done! Laravel now accessible at: $URL"
