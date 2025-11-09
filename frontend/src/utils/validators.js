export const validators = {
  email: (value) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(value) || "Email inválido";
  },

  password: (value) => {
    if (!value) return "Senha obrigatória";
    if (value.length < 8) return "Senha deve ter pelo menos 8 caracteres";
    if (!/[A-Z]/.test(value)) return "Senha deve conter letra maiúscula";
    if (!/[a-z]/.test(value)) return "Senha deve conter letra minúscula";
    if (!/[0-9]/.test(value)) return "Senha deve conter número";
    return true;
  },

  phone: (value) => {
    const re = /^(\d{2})\s?(\d{4,5})\s?(\d{4})$/;
    return re.test(value) || "Telefone inválido";
  },

  zipCode: (value) => {
    const re = /^\d{5}-?\d{3}$/;
    return re.test(value) || "CEP inválido";
  },

  required: (value) => {
    return !!value || "Campo obrigatório";
  },

  minLength: (length) => (value) => {
    return !value || value.length >= length || `Mínimo ${length} caracteres`;
  },

  maxLength: (length) => (value) => {
    return !value || value.length <= length || `Máximo ${length} caracteres`;
  },
};
