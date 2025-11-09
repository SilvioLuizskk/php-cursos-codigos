import { ref, reactive } from 'vue'

export function useForm(initialValues = {}, validationRules = {}) {
  const formData = reactive({ ...initialValues })
  const errors = ref({})
  const isSubmitting = ref(false)

  function validateField(field, value) {
    const rules = validationRules[field]
    if (!rules) return true

    for (const rule of rules) {
      const result = rule(value)
      if (result !== true) {
        errors.value[field] = result
        return false
      }
    }

    delete errors.value[field]
    return true
  }

  function validateForm() {
    let isValid = true
    
    for (const field in validationRules) {
      const fieldValid = validateField(field, formData[field])
      if (!fieldValid) {
        isValid = false
      }
    }

    return isValid
  }

  function resetForm() {
    Object.assign(formData, initialValues)
    errors.value = {}
  }

  function setField(field, value) {
    formData[field] = value
    validateField(field, value)
  }

  async function handleSubmit(submitFn) {
    if (!validateForm()) {
      return false
    }

    isSubmitting.value = true
    
    try {
      await submitFn(formData)
      return true
    } catch (error) {
      console.error('Erro ao enviar formulário:', error)
      return false
    } finally {
      isSubmitting.value = false
    }
  }

  return {
    formData,
    errors,
    isSubmitting,
    validateField,
    validateForm,
    resetForm,
    setField,
    handleSubmit,
  }
}