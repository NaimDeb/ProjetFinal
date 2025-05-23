
export const formatCurrency = (amount: number) => {
  return (amount / 100).toLocaleString('en-US', {
    style: 'currency',
    currency: 'USD',
  });
};

export function cn(...classes: (string | undefined | false)[]) {
    return classes.filter(Boolean).join(" ");
  }

export const formatDateToLocal = (
  dateStr: string,
  locale: string = 'en-US',
) => {
  const date = new Date(dateStr);
  const options: Intl.DateTimeFormatOptions = {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  };
  const formatter = new Intl.DateTimeFormat(locale, options);
  return formatter.format(date);
};


export const generatePagination = (currentPage: number, totalPages: number) => {
  // If the total number of pages is 7 or less,
  // display all pages without any ellipsis.
  if (totalPages <= 7) {
    return Array.from({ length: totalPages }, (_, i) => i + 1);
  }

  // If the current page is among the first 3 pages,
  // show the first 3, an ellipsis, and the last 2 pages.
  if (currentPage <= 3) {
    return [1, 2, 3, '...', totalPages - 1, totalPages];
  }

  // If the current page is among the last 3 pages,
  // show the first 2, an ellipsis, and the last 3 pages.
  if (currentPage >= totalPages - 2) {
    return [1, 2, '...', totalPages - 2, totalPages - 1, totalPages];
  }

  // If the current page is somewhere in the middle,
  // show the first page, an ellipsis, the current page and its neighbors,
  // another ellipsis, and the last page.
  return [
    1,
    '...',
    currentPage - 1,
    currentPage,
    currentPage + 1,
    '...',
    totalPages,
  ];
};

export enum IgdbImageFormat {
  Thumbnail = "t_thumb",
  CoverBig2x = "t_cover_big_2x",
  CoverBig = "t_cover_big",
  CoverSmall = "t_cover_small",
  ScreenshotBig = "t_screenshot_big",
  ScreenshotMed = "t_screenshot_med",
}

export function changeIgdbImageFormat(imageUrl: string, format: IgdbImageFormat) {
  return imageUrl.replace(IgdbImageFormat.Thumbnail, format);
}

export function colorizeContent(content: string) {
  return content
    .replace(/\[buff\](.*?)\[\/buff\]/g, '<span class="buff">$1</span>')
    .replace(/\[debuff\](.*?)\[\/debuff\]/g, '<span class="debuff">$1</span>');
}