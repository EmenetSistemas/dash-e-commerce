import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DetalleClienteComponent } from './detalle-cliente.component';

describe('DetalleClienteComponent', () => {
  let component: DetalleClienteComponent;
  let fixture: ComponentFixture<DetalleClienteComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [DetalleClienteComponent]
    });
    fixture = TestBed.createComponent(DetalleClienteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
