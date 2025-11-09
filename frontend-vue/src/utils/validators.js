/**
 * Utilitários de validação para formulários
 */

// Validar se campo é obrigatório
export const validateRequired = (value, fieldName = "campo") => {
  if (!value || (typeof value === "string" && value.trim() === "")) {
    return `O ${fieldName} é obrigatório`;
  }
  return true;
};

// Validar email
export const validateEmail = (email) => {
  if (!email) {
    return "O email é obrigatório";
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    return "Digite um email válido";
  }

  return true;
};

// Validar senha
export const validatePassword = (password) => {
  if (!password) {
    return "A senha é obrigatória";
  }

  if (password.length < 8) {
    return "A senha deve ter pelo menos 8 caracteres";
  }

  // Verificar se contém pelo menos uma letra minúscula
  if (!/[a-z]/.test(password)) {
    return "A senha deve conter pelo menos uma letra minúscula";
  }

  // Verificar se contém pelo menos uma letra maiúscula
  if (!/[A-Z]/.test(password)) {
    return "A senha deve conter pelo menos uma letra maiúscula";
  }

  // Verificar se contém pelo menos um número
  if (!/\d/.test(password)) {
    return "A senha deve conter pelo menos um número";
  }

  // Verificar se contém pelo menos um símbolo
  if (!/[@$!%*?&]/.test(password)) {
    return "A senha deve conter pelo menos um símbolo (@$!%*?&)";
  }

  return true;
};

// Validar confirmação de senha
export const validatePasswordConfirmation = (password, confirmation) => {
  if (!confirmation) {
    return "A confirmação da senha é obrigatória";
  }

  if (password !== confirmation) {
    return "As senhas não conferem";
  }

  return true;
};

// Validar nome
export const validateName = (name) => {
  if (!name) {
    return "O nome é obrigatório";
  }

  if (name.length < 2) {
    return "O nome deve ter pelo menos 2 caracteres";
  }

  if (name.length > 255) {
    return "O nome não pode ter mais de 255 caracteres";
  }

  // Verificar se contém apenas letras e espaços
  const nameRegex = /^[a-zA-ZÀ-ÿ\s]+$/;
  if (!nameRegex.test(name)) {
    return "O nome deve conter apenas letras e espaços";
  }

  return true;
};

// Validar telefone
export const validatePhone = (phone) => {
  if (!phone) {
    return true; // Campo opcional
  }

  // Remover todos os caracteres não numéricos
  const cleanPhone = phone.replace(/\D/g, "");

  // Verificar se tem 10 ou 11 dígitos (com DDD)
  if (cleanPhone.length < 10 || cleanPhone.length > 11) {
    return "Digite um telefone válido (ex: (11) 99999-9999)";
  }

  return true;
};

// Validar CPF
export const validateCPF = (cpf) => {
  if (!cpf) {
    return true; // Campo opcional
  }

  // Remover caracteres não numéricos
  const cleanCPF = cpf.replace(/\D/g, "");

  // Verificar se tem 11 dígitos
  if (cleanCPF.length !== 11) {
    return "CPF deve ter 11 dígitos";
  }

  // Verificar se não são todos números iguais
  if (/^(\d)\1{10}$/.test(cleanCPF)) {
    return "CPF inválido";
  }

  // Validar dígitos verificadores
  let sum = 0;
  let remainder;

  // Primeiro dígito
  for (let i = 1; i <= 9; i++) {
    sum += parseInt(cleanCPF.substring(i - 1, i)) * (11 - i);
  }
  remainder = (sum * 10) % 11;
  if (remainder === 10 || remainder === 11) remainder = 0;
  if (remainder !== parseInt(cleanCPF.substring(9, 10))) {
    return "CPF inválido";
  }

  // Segundo dígito
  sum = 0;
  for (let i = 1; i <= 10; i++) {
    sum += parseInt(cleanCPF.substring(i - 1, i)) * (12 - i);
  }
  remainder = (sum * 10) % 11;
  if (remainder === 10 || remainder === 11) remainder = 0;
  if (remainder !== parseInt(cleanCPF.substring(10, 11))) {
    return "CPF inválido";
  }

  return true;
};

// Validar CEP
export const validateCEP = (cep) => {
  if (!cep) {
    return "O CEP é obrigatório";
  }

  // Remover caracteres não numéricos
  const cleanCEP = cep.replace(/\D/g, "");

  // Verificar se tem 8 dígitos
  if (cleanCEP.length !== 8) {
    return "CEP deve ter 8 dígitos";
  }

  return true;
};

// Validar número mínimo
export const validateMin = (value, min, fieldName = "valor") => {
  if (value === null || value === undefined || value === "") {
    return true; // Deixa validação de obrigatório para validateRequired
  }

  const numValue = parseFloat(value);
  if (isNaN(numValue) || numValue < min) {
    return `O ${fieldName} deve ser pelo menos ${min}`;
  }

  return true;
};

// Validar número máximo
export const validateMax = (value, max, fieldName = "valor") => {
  if (value === null || value === undefined || value === "") {
    return true; // Deixa validação de obrigatório para validateRequired
  }

  const numValue = parseFloat(value);
  if (isNaN(numValue) || numValue > max) {
    return `O ${fieldName} deve ser no máximo ${max}`;
  }

  return true;
};

// Validar comprimento mínimo
export const validateMinLength = (value, minLength, fieldName = "campo") => {
  if (!value) {
    return true; // Deixa validação de obrigatório para validateRequired
  }

  if (value.length < minLength) {
    return `O ${fieldName} deve ter pelo menos ${minLength} caracteres`;
  }

  return true;
};

// Validar comprimento máximo
export const validateMaxLength = (value, maxLength, fieldName = "campo") => {
  if (!value) {
    return true; // Deixa validação de obrigatório para validateRequired
  }

  if (value.length > maxLength) {
    return `O ${fieldName} não pode ter mais de ${maxLength} caracteres`;
  }

  return true;
};

// Validar URL
export const validateURL = (url) => {
  if (!url) {
    return true; // Campo opcional
  }

  try {
    new URL(url);
    return true;
  } catch {
    return "Digite uma URL válida";
  }
};

// Validar se é número
export const validateNumber = (value, fieldName = "valor") => {
  if (value === null || value === undefined || value === "") {
    return true; // Deixa validação de obrigatório para validateRequired
  }

  if (isNaN(value)) {
    return `O ${fieldName} deve ser um número válido`;
  }

  return true;
};

// Validar se é número inteiro
export const validateInteger = (value, fieldName = "valor") => {
  if (value === null || value === undefined || value === "") {
    return true; // Deixa validação de obrigatório para validateRequired
  }

  if (!Number.isInteger(Number(value))) {
    return `O ${fieldName} deve ser um número inteiro`;
  }

  return true;
};

// Validador composto - executa múltiplas validações
export const validateField = (value, validators = []) => {
  for (const validator of validators) {
    const result = validator(value);
    if (result !== true) {
      return result;
    }
  }
  return true;
};

// Validar formulário completo
export const validateForm = (data, rules) => {
  const errors = {};

  for (const [field, fieldRules] of Object.entries(rules)) {
    const value = data[field];
    const result = validateField(value, fieldRules);

    if (result !== true) {
      errors[field] = [result];
    }
  }

  return {
    isValid: Object.keys(errors).length === 0,
    errors,
  };
};

// Máscaras para formatação
export const masks = {
  phone: (value) => {
    const cleaned = value.replace(/\D/g, "");
    if (cleaned.length <= 10) {
      return cleaned.replace(/(\d{2})(\d{4})(\d{4})/, "($1) $2-$3");
    }
    return cleaned.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
  },

  cpf: (value) => {
    const cleaned = value.replace(/\D/g, "");
    return cleaned.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
  },

  cep: (value) => {
    const cleaned = value.replace(/\D/g, "");
    return cleaned.replace(/(\d{5})(\d{3})/, "$1-$2");
  },

  currency: (value) => {
    const numValue = parseFloat(value.toString().replace(/\D/g, "")) / 100;
    return new Intl.NumberFormat("pt-BR", {
      style: "currency",
      currency: "BRL",
    }).format(numValue);
  },
};
