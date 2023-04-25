import { ComponentFixture, TestBed } from '@angular/core/testing';

import { JotcComponent } from './jotc.component';

describe('JotcComponent', () => {
  let component: JotcComponent;
  let fixture: ComponentFixture<JotcComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ JotcComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(JotcComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
