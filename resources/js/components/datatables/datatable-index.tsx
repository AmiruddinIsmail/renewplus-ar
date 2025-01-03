export default function DataTableIndex(
    page: number,
    table: any,
    row: any,
    perPageCount: number,
) {
    const pageNumber = page > 1 ? (page - 1) * perPageCount : 0;
    return (
        (table
            .getSortedRowModel()
            ?.flatRows?.findIndex((flatRow: any) => flatRow.id === row.id) ||
            0) +
        1 +
        pageNumber
    );
}
