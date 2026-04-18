import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import { PostType } from '@/types/post';

export function exportPostsPdf(
  posts: PostType[],
  dateStart: string,
  dateEnd: string,
) {
  const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });

  const pageWidth = doc.internal.pageSize.getWidth();

  // Cabeçalho
  doc.setFont('helvetica', 'bold');
  doc.setFontSize(18);
  doc.setTextColor(37, 99, 235); // blue-600
  doc.text('Blog O Povo', pageWidth / 2, 18, { align: 'center' });

  doc.setFont('helvetica', 'normal');
  doc.setFontSize(11);
  doc.setTextColor(107, 114, 128); // gray-500
  doc.text('Relatório de Posts', pageWidth / 2, 25, { align: 'center' });

  const period = `Período: ${formatDate(dateStart)} a ${formatDate(dateEnd)}`;
  doc.text(period, pageWidth / 2, 31, { align: 'center' });

  doc.setFontSize(10);
  doc.text(
    `Gerado em: ${format(new Date(), "dd/MM/yyyy 'às' HH:mm", { locale: ptBR })}`,
    pageWidth / 2,
    37,
    { align: 'center' },
  );

  // Linha separadora
  doc.setDrawColor(229, 231, 235);
  doc.line(14, 41, pageWidth - 14, 41);

  // Resumo
  doc.setFont('helvetica', 'bold');
  doc.setFontSize(11);
  doc.setTextColor(17, 24, 39);
  doc.text(`Total de posts: ${posts.length}`, 14, 49);

  // Tabela
  autoTable(doc, {
    startY: 55,
    head: [['#', 'Título', 'Autor', 'Data de criação']],
    body: posts.map((post, i) => [
      i + 1,
      post.title,
      post.authorUser.name,
      format(new Date(post.createdAt), 'dd/MM/yyyy HH:mm', { locale: ptBR }),
    ]),
    headStyles: {
      fillColor: [37, 99, 235],
      textColor: 255,
      fontStyle: 'bold',
      fontSize: 10,
    },
    bodyStyles: { fontSize: 9, textColor: [55, 65, 81] },
    alternateRowStyles: { fillColor: [249, 250, 251] },
    columnStyles: {
      0: { cellWidth: 10, halign: 'center' },
      1: { cellWidth: 90 },
      2: { cellWidth: 45 },
      3: { cellWidth: 35, halign: 'center' },
    },
    margin: { left: 14, right: 14 },
    styles: { overflow: 'linebreak', cellPadding: 3 },
  });

  const fileName = `relatorio-posts-${dateStart}-${dateEnd}.pdf`;
  doc.save(fileName);
}

function formatDate(dateStr: string) {
  if (!dateStr) return '-';
  return format(new Date(dateStr), 'dd/MM/yyyy', { locale: ptBR });
}
