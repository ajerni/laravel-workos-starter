<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'

const files = ref<Array<{
  id: number;
  original_name: string;
  type: string;
  uploaded_at: string;
}>>([])
const uploading = ref(false)
const uploadError = ref<string | null>(null)
const fileInput = ref<HTMLInputElement | null>(null)
const selectedFile = ref<File | null>(null)

// Get CSRF token
const getCsrfToken = () => {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
}

// Fetch FAQ files
const fetchFiles = async () => {
  try {
    const response = await fetch('/api/faq-files', {
      credentials: 'same-origin',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      }
    })
    
    if (response.ok) {
      const data = await response.json()
      files.value = data
    } else {
      files.value = []
    }
  } catch (e) {
    files.value = []
  }
}

onMounted(fetchFiles)

// Handle file selection
const handleFileSelect = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    selectedFile.value = target.files[0]
    uploadError.value = null
  } else {
    selectedFile.value = null
  }
}

// Upload file
const uploadFile = async () => {
  if (!selectedFile.value) {
    uploadError.value = 'Please select a file first'
    return
  }
  
  const formData = new FormData()
  formData.append('file', selectedFile.value)
  
  uploading.value = true
  uploadError.value = null
  
  try {
    const response = await fetch('/api/faq-files', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': getCsrfToken(),
        'Accept': 'application/json',
      },
      body: formData
    })
    
    if (response.ok) {
      await fetchFiles()
      if (fileInput.value) fileInput.value.value = ''
      selectedFile.value = null
      uploadError.value = null
    } else {
      const errorData = await response.json()
      uploadError.value = errorData.message || `Upload failed (${response.status})`
    }
  } catch (e: unknown) {
    uploadError.value = 'Upload failed.'
  } finally {
    uploading.value = false
  }
}

// Delete file
const deleteFile = async (id: number) => {
  if (!confirm('Delete this file?')) return
  
  try {
    const response = await fetch(`/api/faq-files/${id}`, {
      method: 'DELETE',
      credentials: 'same-origin',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': getCsrfToken(),
        'Accept': 'application/json',
      }
    })
    
    if (response.ok) {
      await fetchFiles()
    }
  } catch (e) {
    // handle error silently
  }
}
</script>

<template>
  <Card class="w-full">
    <CardHeader>
      <CardTitle>FAQ File Manager</CardTitle>
    </CardHeader>
    <CardContent>
      <form @submit.prevent>
        <Input
          ref="fileInput"
          type="file"
          accept=".pdf,.doc,.docx,.txt,.md"
          :disabled="uploading"
          @change="handleFileSelect"
        />
        <Button
          class="mt-2"
          :disabled="uploading || !selectedFile"
          @click="uploadFile"
        >
          {{ uploading ? 'Uploading...' : 'Upload' }}
        </Button>
        <div
          v-if="uploadError"
          class="text-red-500 mt-2"
        >
          {{ uploadError }}
        </div>
      </form>
      <div class="mt-6">
        <h3 class="font-semibold mb-2">
          Your FAQ Files
        </h3>
        <ul v-if="files.length">
          <li
            v-for="file in files"
            :key="file.id"
            class="flex items-center justify-between border-b py-2"
          >
            <span>{{ file.original_name }} ({{ file.type }})</span>
            <Button
              variant="destructive"
              size="sm"
              @click="deleteFile(file.id)"
            >
              Delete
            </Button>
          </li>
        </ul>
        <div
          v-else
          class="text-muted-foreground"
        >
          No files uploaded yet.
        </div>
      </div>
    </CardContent>
  </Card>
</template> 