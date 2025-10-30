FROM node:22-alpine

WORKDIR /app

# Copiar package files
COPY package*.json ./

# Instalar dependencias
RUN npm ci

# Copiar código fuente
COPY . .

# Exponer puerto
EXPOSE 3000

# Comando de desarrollo
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]