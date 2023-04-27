import { DataSource } from '@angular/cdk/collections';
import { MatPaginator } from '@angular/material/paginator';
import { MatSort } from '@angular/material/sort';
import { map } from 'rxjs/operators';
import { Observable, of as observableOf, merge, BehaviorSubject } from 'rxjs';
import { DataService } from 'src/app/services/data.service';

// TODO: Replace this with your own data model type
export interface DashboardItem {
  id: number;
  user_id: number;
  input: string;
  output: string;
  date: string;
}

/**
 * Data source for the Dashboard view. This class should
 * encapsulate all logic for fetching and manipulating the displayed data
 * (including sorting, pagination, and filtering).
 */
export class DashboardDataSource extends DataSource<DashboardItem> {
  data: any;
  paginator: MatPaginator | undefined;
  sort: MatSort | undefined;
  filter: any;
  dateBegin: any;
  dateEnd: any;
  totalRows = 0;

  constructor(private dataService: DataService) {
    super();
  }

  private itemSubject = new BehaviorSubject<any>([]);

  getSubmissions(){
    let queryParams = '';

    if (this.paginator){
      queryParams += 'page=' + this.paginator.pageIndex + '&pageSize=' + this.paginator.pageSize;
    }

    if (!this.sort || !this.sort.active || this.sort.direction === '') {
      
    }else{
      queryParams += '&sort=' + this.sort?.direction + '&sortBy=' + this.sort?.active;
    }

    if (this.filter){
      queryParams += '&email=' + this.filter;
    }

    if (this.dateBegin){
      queryParams += '&dateBegin=' + this.dateBegin;
    }

    if (this.dateEnd){
      queryParams += '&dateEnd=' + this.dateEnd;
    }


    this.dataService.get('http://localhost:4000/jotc', queryParams).subscribe( (res: any) => {
      this.itemSubject.next(res);
      this.totalRows = res[0]?.total_rows;
    });

  }

  /**
   * Connect this data source to the table. The table will only update when
   * the returned stream emits new items.
   * @returns A stream of the items to be rendered.
   */
  connect(): Observable<any> {
    
    return this.itemSubject.asObservable();

    /*if (this.paginator && this.sort) {
      // Combine everything that affects the rendered data into one update
      // stream for the data-table to consume.
      return merge(observableOf(this.data), this.paginator.page, this.sort.sortChange)
        .pipe(map(() => {
          return this.getPagedData(this.getSortedData([...this.data ]));
        }));
    } else {
      throw Error('Please set the paginator and sort on the data source before connecting.');
    }*/
  }

  /**
   *  Called when the table is being destroyed. Use this function, to clean up
   * any open connections or free any held resources that were set up during connect.
   */
  disconnect(): void {
    this.itemSubject.complete();
  }

  /**
   * Paginate the data (client-side). If you're using server-side pagination,
   * this would be replaced by requesting the appropriate data from the server.
   */
  /*private getPagedData(data: DashboardItem[]): DashboardItem[] {
    if (this.paginator) {
      const startIndex = this.paginator.pageIndex * this.paginator.pageSize;
      return data.splice(startIndex, this.paginator.pageSize);
    } else {
      return data;
    }
  }*/

  /**
   * Sort the data (client-side). If you're using server-side sorting,
   * this would be replaced by requesting the appropriate data from the server.
   */
  /*
  private getSortedData(data: DashboardItem[]): DashboardItem[] {
    if (!this.sort || !this.sort.active || this.sort.direction === '') {
      return data;
    }

    return data.sort((a, b) => {
      const isAsc = this.sort?.direction === 'asc';
      switch (this.sort?.active) {
        case 'name': return compare(a.name, b.name, isAsc);
        case 'id': return compare(+a.id, +b.id, isAsc);
        default: return 0;
      }
    });
  }
  */
}

/** Simple sort comparator for example ID/Name columns (for client-side sorting). */
function compare(a: string | number, b: string | number, isAsc: boolean): number {
  return (a < b ? -1 : 1) * (isAsc ? 1 : -1);
}
