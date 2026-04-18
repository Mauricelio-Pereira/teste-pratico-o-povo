type Size = 'sm' | 'md' | 'lg';

const sizeClasses: Record<Size, string> = {
  sm: 'w-4 h-4 border-2',
  md: 'w-6 h-6 border-2',
  lg: 'w-10 h-10 border-4',
};

type LoadingSpinnerProps = {
  size?: Size;
  className?: string;
};

export function LoadingSpinner({ size = 'md', className = '' }: LoadingSpinnerProps) {
  return (
    <span
      className={`
        block rounded-full border-current border-t-transparent animate-spin
        ${sizeClasses[size]} ${className}
      `}
      aria-label="Carregando..."
    />
  );
}

export function PageLoader() {
  return (
    <div className="flex items-center justify-center min-h-[300px]">
      <LoadingSpinner size="lg" className="text-blue-600" />
    </div>
  );
}
