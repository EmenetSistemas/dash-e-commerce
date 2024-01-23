import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ConsultaProductosComponent } from './consulta-productos.component';

describe('ConsultaProductosComponent', () => {
  let component: ConsultaProductosComponent;
  let fixture: ComponentFixture<ConsultaProductosComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ConsultaProductosComponent]
    });
    fixture = TestBed.createComponent(ConsultaProductosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
