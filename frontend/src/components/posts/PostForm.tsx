'use client';

import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { Input } from '@/components/ui/Input';
import { Textarea } from '@/components/ui/Textarea';
import { Button } from '@/components/ui/Button';

const schema = z.object({
  title: z.string().min(1, { message: 'Título obrigatório' }).max(255, { message: 'Título muito longo' }),
  content: z.string().min(1, { message: 'Conteúdo obrigatório' }),
});

export type PostFormData = z.infer<typeof schema>;

type PostFormProps = {
  defaultValues?: Partial<PostFormData>;
  onSubmit: (data: PostFormData) => Promise<void>;
  submitLabel?: string;
};

export function PostForm({ defaultValues, onSubmit, submitLabel = 'Salvar' }: PostFormProps) {
  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
  } = useForm<PostFormData>({
    resolver: zodResolver(schema),
    defaultValues,
  });

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="flex flex-col gap-5">
      <Input
        id="title"
        label="Título"
        placeholder="Digite o título do post"
        error={errors.title?.message}
        {...register('title')}
      />

      <Textarea
        id="content"
        label="Conteúdo"
        placeholder="Escreva o conteúdo do post..."
        rows={12}
        error={errors.content?.message}
        {...register('content')}
      />

      <div className="flex justify-end">
        <Button type="submit" loading={isSubmitting} size="lg">
          {submitLabel}
        </Button>
      </div>
    </form>
  );
}
