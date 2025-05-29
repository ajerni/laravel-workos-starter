<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import { Checkbox } from '@/components/ui/checkbox'
import { Switch } from '@/components/ui/switch'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Home',
    href: '/home',
  },
]

const isChecked = ref(false)

// Load Flowise chatbot
onMounted(() => {
  // Create script element for Flowise
  const script = document.createElement('script')
  script.src = 'https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js'
  script.type = 'module'
  script.onload = () => {
    // Initialize chatbot after script loads
    ;(window as any).Chatbot.init({
      chatflowid: "1e466e6a-1b33-411e-8840-12e8c9dd2fc1",
      apiHost: "https://flowise.ernilabs.com",
    })
  }
  document.head.appendChild(script)
})
</script>

<template>
  <Head title="Home" />

  <AppLayout
    :breadcrumbs="breadcrumbs"
    title="Home"
  >
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="rounded-xl border border-neutral-200 p-8 text-center text-lg text-muted-foreground">
        Welcome to the Home page! Placeholder content goes here.
      </div>
      <Card class="w-full">
        <CardHeader>
          <CardTitle>
            Card Title here Hallöle
          </CardTitle>
        </CardHeader>
        <CardContent>
          <p>Button:</p>
          <Button class="mt-2 cursor-pointer">
            Click me
          </Button>
        </CardContent>
        <CardFooter>
          <div class="flex flex-col gap-2">
            <p>Switch:</p>
            <div class="flex items-center gap-2">
              <Switch v-model="isChecked" />
              <p>{{ isChecked ? 'ON' : 'OFF' }}</p>
            </div>
            <Checkbox v-model="isChecked" />
          </div>
        </CardFooter>
      </Card>
    </div>
  </AppLayout>
</template>