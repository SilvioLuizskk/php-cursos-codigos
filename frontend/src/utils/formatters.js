export function formatPrice(value) {
  return new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL",
  }).format(value);
}

export function formatDate(date) {
  return new Intl.DateTimeFormat("pt-BR").format(new Date(date));
}

export function formatDateTime(date) {
  return new Intl.DateTimeFormat("pt-BR", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
  }).format(new Date(date));
}

export function formatPhone(value) {
  return value.replace(/(\d{2})(\d{4,5})(\d{4})/, "($1) $2-$3");
}

export function formatZipCode(value) {
  return value.replace(/(\d{5})(\d{3})/, "$1-$2");
}

export function truncate(text, length = 50) {
  return text.length > length ? text.substring(0, length) + "..." : text;
}
