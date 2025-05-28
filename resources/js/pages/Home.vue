<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Switch } from '@/components/ui/switch';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Home',
        href: '/home',
    },
];

const isChecked = ref(false);
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
          </div>
        </CardFooter>
      </Card>
    </div>
  </AppLayout>
</template> 